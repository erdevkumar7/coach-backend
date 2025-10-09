<?php

namespace App\Http\Controllers\Api;

use App\Models\UserServicePackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Collection;

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

        $bookings = UserServicePackage::where('coach_id', $coach_id)
            ->whereDate('booking_availability_end', '>=', Carbon::today())
            ->get(['booking_availability_start', 'booking_availability_end']);

        if ($bookings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No upcoming availability found.'
            ], 404);
        }

        // Step 1: Build and normalize all date ranges (future only)
        $ranges = $bookings->map(function ($item) {
            $start = Carbon::parse($item->booking_availability_start)->toDateString();
            $end   = Carbon::parse($item->booking_availability_end)->toDateString();
            return [$start, $end];
        })->toArray();

        // Step 2: Sort ranges by start date
        usort($ranges, function ($a, $b) {
            return strcmp($a[0], $b[0]);
        });

        // Step 3: Merge overlapping or continuous date ranges
        $merged = [];
        $current = null;

        foreach ($ranges as $range) {
            [$start, $end] = $range;

            if (!$current) {
                $current = [$start, $end];
                continue;
            }

            // If overlapping or continuous (next start <= current end + 1 day)
            if (Carbon::parse($start)->lte(Carbon::parse($current[1])->addDay())) {
                $current[1] = max($current[1], $end);
            } else {
                $merged[] = $current;
                $current = [$start, $end];
            }
        }

        if ($current) {
            $merged[] = $current;
        }

        // Step 4: Filter out any ranges that end before today
        $merged = array_filter($merged, function ($range) {
            return Carbon::parse($range[1])->gte(Carbon::today());
        });

        return response()->json([
            'success' => true,
            'message' => 'Coach available date ranges fetched successfully.',
            'availability' => [
                'available' => array_values($merged)
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while fetching data.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
