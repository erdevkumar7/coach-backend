<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingPackages;
use App\Models\UserServicePackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class CalendarBookingController extends Controller
{
    public function coachCalendarBookingDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coach_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $coach_id = $request->input('coach_id');

            $booking = UserServicePackage::where('coach_id', $coach_id)
                ->get();

            if ($booking->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No service package found'
                ], 404);
            }

            // Prepare response with selected fields
            $data = $booking->map(function ($item) {
                return [
                    'id' => $item->id,
                    'coach_id' => $item->coach_id,
                    'title' => $item->title,
                    'booking_slots' => $item->booking_slots,
                    'booking_availability' => $item->booking_availability,
                    'booking_window' => $item->booking_window,
                    // 'user' => [
                    //     'id' => $item->user->id ?? null,
                    //     'first_name' => $item->user->first_name ?? null,
                    //     'last_name' => $item->user->last_name ?? null,
                    // ]
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'All bookings of services package coach',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function calendarStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coach_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $coach_id = $request->input('coach_id');

            // 🔹 1. Fetch availability (from user_service_packages)
            $availabilityData = UserServicePackage::where('coach_id', $coach_id)
                ->whereDate('booking_availability_end', '>=', Carbon::today())
                ->get(['id', 'booking_availability_start', 'booking_availability_end', 'booking_slots']);

            $availableRanges = [];
            $packageSlots = []; // store slot limits by date

            foreach ($availabilityData as $item) {
                $start = Carbon::parse($item->booking_availability_start)->toDateString();
                $end = Carbon::parse($item->booking_availability_end)->toDateString();

                $availableRanges[] = [$start, $end];

                // Store per-day slot limits for unavailable check
                $period = new \DatePeriod(
                    new Carbon($start),
                    new \DateInterval('P1D'),
                    (new Carbon($end))->addDay()
                );

                foreach ($period as $date) {
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $packageSlots[$carbonDate->toDateString()] = $item->booking_slots;
                }
            }

            // 🔹 2. Merge overlapping ranges
            usort($availableRanges, fn($a, $b) => strcmp($a[0], $b[0]));
            $merged = [];
            $current = null;

            foreach ($availableRanges as $range) {
                [$start, $end] = $range;
                if (!$current) {
                    $current = [$start, $end];
                    continue;
                }

                if (Carbon::parse($start)->lte(Carbon::parse($current[1])->addDay())) {
                    $current[1] = max($current[1], $end);
                } else {
                    $merged[] = $current;
                    $current = [$start, $end];
                }
            }
            if ($current)
                $merged[] = $current;

            $availableRanges = array_filter($merged, fn($r) => Carbon::parse($r[1])->gte(Carbon::today()));

            // 🔹 3. Fetch booked slots (from booking_packages)
            $bookedData = BookingPackages::where('coach_id', $coach_id)
                ->whereDate('session_date_start', '>=', Carbon::today())
                ->orderBy('session_date_start', 'asc')
                ->get(['session_date_start', 'slot_time_start', 'slot_time_end']);

            $bookedSlots = [];
            foreach ($bookedData as $item) {
                $date = Carbon::parse($item->session_date_start)->toDateString();
                $bookedSlots[$date][] = [
                    'start_time' => $item->slot_time_start,
                    'end_time' => $item->slot_time_end,
                ];
            }

            // 🔹 4. Determine fully booked (unavailable) days
            $unavailableDates = [];
            foreach ($packageSlots as $date => $totalSlots) {
                $bookedCount = isset($bookedSlots[$date]) ? count($bookedSlots[$date]) : 0;
                if ($bookedCount >= $totalSlots && $totalSlots > 0) {
                    $unavailableDates[] = $date;
                }
            }

            // 🔹 5. Remove unavailable days from available ranges
            $availableRanges = array_map(function ($range) use ($unavailableDates) {
                [$start, $end] = $range;

                $period = new \DatePeriod(
                    new Carbon($start),
                    new \DateInterval('P1D'),
                    (new Carbon($end))->addDay()
                );

                $validDates = [];
                foreach ($period as $date) {
                    $dateStr = Carbon::parse($date)->toDateString();
                    if (!in_array($dateStr, $unavailableDates)) {
                        $validDates[] = $dateStr;
                    }
                }

                if (empty($validDates))
                    return null;
                return [reset($validDates), end($validDates)];
            }, $availableRanges);

            $availableRanges = array_values(array_filter($availableRanges));

            // 🔹 6. Final response
            return response()->json([
                'success' => true,
                'message' => 'Coach calendar status fetched successfully.',
                'availability' => [
                    'available' => $availableRanges,
                    'booked' => $bookedSlots,
                    'unavailable' => $unavailableDates
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching calendar status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function calendarStatusDashboard(Request $request)
    {
        try {

            $user = Auth::user(); // Authenticated user

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 403);
            }

            // ✅ Check if user_type is 3
            if ($user->user_type != 3 || $user->is_deleted != 0 || $user->user_status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.',
                ], 403);
            }

            $coach_id = $user->id;

            // 🔹 1. Fetch availability (from user_service_packages)
            $availabilityData = UserServicePackage::where('coach_id', $coach_id)
                ->whereDate('booking_availability_end', '>=', Carbon::today())
                ->get(['id', 'booking_availability_start', 'booking_availability_end', 'booking_slots']);

            $availableRanges = [];
            $packageSlots = []; // store slot limits by date

            foreach ($availabilityData as $item) {
                $start = Carbon::parse($item->booking_availability_start)->toDateString();
                $end = Carbon::parse($item->booking_availability_end)->toDateString();

                $availableRanges[] = [$start, $end];

                // Store per-day slot limits for unavailable check
                $period = new \DatePeriod(
                    new Carbon($start),
                    new \DateInterval('P1D'),
                    (new Carbon($end))->addDay()
                );

                foreach ($period as $date) {
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $packageSlots[$carbonDate->toDateString()] = $item->booking_slots;
                }
            }

            // 🔹 2. Merge overlapping ranges
            usort($availableRanges, fn($a, $b) => strcmp($a[0], $b[0]));
            $merged = [];
            $current = null;

            foreach ($availableRanges as $range) {
                [$start, $end] = $range;
                if (!$current) {
                    $current = [$start, $end];
                    continue;
                }

                if (Carbon::parse($start)->lte(Carbon::parse($current[1])->addDay())) {
                    $current[1] = max($current[1], $end);
                } else {
                    $merged[] = $current;
                    $current = [$start, $end];
                }
            }
            if ($current)
                $merged[] = $current;

            $availableRanges = array_filter($merged, fn($r) => Carbon::parse($r[1])->gte(Carbon::today()));

            // 🔹 3. Fetch booked slots (from booking_packages)
            $bookedData = BookingPackages::where('coach_id', $coach_id)
                ->whereDate('session_date_start', '>=', Carbon::today())
                ->orderBy('session_date_start', 'asc')
                ->get(['session_date_start', 'slot_time_start', 'slot_time_end']);

            $bookedSlots = [];
            foreach ($bookedData as $item) {
                $date = Carbon::parse($item->session_date_start)->toDateString();
                $bookedSlots[$date][] = [
                    'start_time' => $item->slot_time_start,
                    'end_time' => $item->slot_time_end,
                ];
            }

            // 🔹 4. Determine fully booked (unavailable) days
            $unavailableDates = [];
            foreach ($packageSlots as $date => $totalSlots) {
                $bookedCount = isset($bookedSlots[$date]) ? count($bookedSlots[$date]) : 0;
                if ($bookedCount >= $totalSlots && $totalSlots > 0) {
                    $unavailableDates[] = $date;
                }
            }

            // 🔹 5. Remove unavailable days from available ranges
            $availableRanges = array_map(function ($range) use ($unavailableDates) {
                [$start, $end] = $range;

                $period = new \DatePeriod(
                    new Carbon($start),
                    new \DateInterval('P1D'),
                    (new Carbon($end))->addDay()
                );

                $validDates = [];
                foreach ($period as $date) {
                    $dateStr = Carbon::parse($date)->toDateString();
                    if (!in_array($dateStr, $unavailableDates)) {
                        $validDates[] = $dateStr;
                    }
                }

                if (empty($validDates))
                    return null;
                return [reset($validDates), end($validDates)];
            }, $availableRanges);

            $availableRanges = array_values(array_filter($availableRanges));

            // 🔹 6. Final response
            return response()->json([
                'success' => true,
                'message' => 'Coach calendar status fetched successfully.',
                'availability' => [
                    'available' => $availableRanges,
                    'booked' => $bookedSlots,
                    'unavailable' => $unavailableDates
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching calendar status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
