<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\CoachingRequest;
use App\Models\BookingPackages;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    
    // public function CoachConfirmedBooking(Request $request)
    // {
    //     $coach_id = Auth::id();
    //     $date = $request->input('date'); // Optional filter for specific date

    //     try {
    //         $bookings = BookingPackages::with(['user', 'coachPackage'])
    //             ->where('coach_id', $coach_id)
    //             ->when($date, function ($query) use ($date) {
    //                 $query->whereDate('session_date_start', $date);
    //             })
    //             ->where('status', 1) 
    //             ->whereHas('user', function ($query) {
    //                 $query->where('user_type', 2)
    //                     ->where('email_verified', 1)
    //                     ->where('user_status', 1)
    //                     ->where('is_deleted', 0)
    //                     ->where('is_verified', 1);
    //             })
    //             ->get();

    //         $grouped = $bookings->groupBy(function ($item) {
    //             return $item->session_date_start;
    //         })->map(function ($bookingsByDate) {
    //             // Group packages under the same date
    //             return [
    //                 'date' => $bookingsByDate->first()->session_date_start,
    //                 'packages' => $bookingsByDate->groupBy('package_id')->map(function ($packageBookings) {
    //                     $package = $packageBookings->first()->coachPackage;
    //                     return [
    //                         'package_id' => $package->id,
    //                         'title' => $package->title,
    //                         'coach_id' => $package->coach_id,
    //                         'users' => $packageBookings->map(function ($booking) {
    //                             return $booking->user;
    //                         })->unique('id')->values()
    //                     ];
    //                 })->values()
    //             ];
    //         })->values();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Grouped booking data by date and package',
    //             'data' => $grouped
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong while fetching data.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function CoachConfirmedBooking(Request $request)
    {
        $coach_id = Auth::id();
        $date = $request->input('date'); // Optional filter for specific date

        try {
            $bookings = BookingPackages::with(['user', 'coachPackage'])
                ->where('coach_id', $coach_id)
                ->when($date, function ($query) use ($date) {
                    $query->whereDate('session_date_start', $date);
                })
                // ->where('status', 1) 
                ->whereHas('user', function ($query) {
                    $query->where('user_type', 2)
                        ->where('email_verified', 1)
                        ->where('user_status', 1)
                        ->where('is_deleted', 0)
                        ->where('is_verified', 1);
                })
                ->get();

            // Group bookings by date
            $grouped = $bookings->groupBy(function ($item) {
                return $item->session_date_start;
            })->map(function ($bookingsByDate) {
                return [
                    'date' => $bookingsByDate->first()->session_date_start,
                    'packages' => $bookingsByDate->groupBy('package_id')->map(function ($packageBookings) {
                        $package = $packageBookings->first()->coachPackage;
                        return [
                            'package_id' => $package->id,
                            'title' => $package->title,
                            'coach_id' => $package->coach_id,
                            'users' => $packageBookings->map(function ($booking) {
                                $user = $booking->user;
                                return [
                                    'id' => $user->id,
                                    'first_name' => $user->first_name,
                                    'last_name' => $user->last_name,
                                    'email' => $user->email,
                                    'slot_time_start' => $booking->slot_time_start,
                                    'status' => $booking->status,
                                ];
                            })->values()
                        ];
                    })->values()
                ];
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Grouped booking data by date and package with slot_time_start',
                'data' => $grouped
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    


}