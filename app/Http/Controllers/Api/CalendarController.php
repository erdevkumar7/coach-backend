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
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Validator;
>>>>>>> 6917bf9824fcb07844dae635e3605c42719024bb

class CalendarController extends Controller
{

<<<<<<< HEAD
public function CoachConfirmedBooking(Request $request)
{
    $coach_id = Auth::id();
    $status = $request->input('status', 0); 

    try {
        $bookings = BookingPackages::with(['user', 'coachPackage'])
            ->where('coach_id', $coach_id)
            ->when($status != 'all', function ($query) use ($status) { 
                $query->where('status', $status);
            })
            ->whereHas('user', function ($query) {
                $query->where('user_type', 2)
                    ->where('email_verified', 1)
                    ->where('user_status', 1)
                    ->where('is_deleted', 0)
                    ->where('is_verified', 1);
            })
            ->whereHas('coachPackage', function ($query) {
                $query->where('package_status', 1)
                      ->where('is_deleted', 0);
            })
            ->get();

        $grouped = $bookings->groupBy(function ($item) {
            return $item->session_date_start;
        })->map(function ($bookingsByDate) {
            return [
                'date' => $bookingsByDate->first()->session_date_start,
                'packages' => $bookingsByDate->groupBy('package_id')->map(function ($packageBookings) {
                    $firstBooking = $packageBookings->first();
                    $package = $firstBooking->coachPackage;

                    if (!$package) {                      
                        return null; 
                    }

                    return [
                        'package_id' => $package->id,
                        'title' => $package->title,
                        'coach_id' => $package->coach_id,
                        'users' => $packageBookings->map(function ($booking) {
                            $user = $booking->user;

                            if (!$user) {                               
                                return null;
                            }

                            return [
                                'id' => $user->id,
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'email' => $user->email,
                                 'profile_image' => $user->profile_image ? asset('public/uploads/profile_image/' . $user->profile_image) : null,
                                'slot_time_start' => $booking->slot_time_start,
                                'status' => $booking->status,
                            ];
                        })->filter()->values() 
                    ];
                })->filter()->values() 
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Grouped booking data by package with status filtering',
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





        public function coachRescheduleBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:booking_packages,id',
            'new_date' => 'required|date|after:now',
            'new_slot_time_start' => 'required|date_format:H:i',
            'new_slot_time_end' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string',
        ]);

        $coach_id = Auth::id(); // Coach is authenticated
=======
    public function CoachConfirmedBooking(Request $request)
    {
        $coach_id = Auth::id();
        $status = $request->input('status', 0); 

        try {
            $bookings = BookingPackages::with(['user', 'coachPackage'])
                ->where('coach_id', $coach_id)
                ->when($status != 'all', function ($query) use ($status) { 
                    $query->where('status', $status);
                })
                ->whereHas('user', function ($query) {
                    $query->where('user_type', 2)
                        ->where('email_verified', 1)
                        ->where('user_status', 1)
                        ->where('is_deleted', 0)
                        ->where('is_verified', 1);
                })
                ->whereHas('coachPackage', function ($query) {
                    $query->where('package_status', 1)
                        ->where('is_deleted', 0);
                })
                ->get();

            $grouped = $bookings->groupBy(function ($item) {
                return $item->session_date_start;
            })->map(function ($bookingsByDate) {
                return [
                    'date' => $bookingsByDate->first()->session_date_start,
                    'packages' => $bookingsByDate->groupBy('package_id')->map(function ($packageBookings) {
                        $firstBooking = $packageBookings->first();
                        $package = $firstBooking->coachPackage;

                        if (!$package) {                      
                            return null; 
                        }

                        return [
                            'package_id' => $package->id,
                            'title' => $package->title,
                            'coach_id' => $package->coach_id,
                            'users' => $packageBookings->map(function ($booking) {
                                $user = $booking->user;

                                if (!$user) {                               
                                    return null;
                                }

                                return [
                                    'id' => $user->id,
                                    'first_name' => $user->first_name,
                                    'last_name' => $user->last_name,
                                    'email' => $user->email,
                                    'profile_image' => $user->profile_image ? asset('public/uploads/profile_image/' . $user->profile_image) : null,
                                    'slot_time_start' => $booking->slot_time_start,
                                    'status' => $booking->status,
                                    'booking_id' => $booking->id,
                                ];
                            })->filter()->values() 
                        ];
                    })->filter()->values() 
                ];
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Grouped booking user data by package with status filtering',
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





    public function coachRescheduleBooking(Request $request)
    {
        $coach_id = Auth::id();

        $validated = $request->validate([
            'booking_id' => 'required|exists:booking_packages,id',
            'new_session_date_start' => 'required|date',
            'new_slot_time_start' => 'required|date_format:H:i',
        ]);
>>>>>>> 6917bf9824fcb07844dae635e3605c42719024bb

        try {
            $booking = BookingPackages::where('id', $request->booking_id)
                ->where('coach_id', $coach_id)
<<<<<<< HEAD
=======
                ->whereIn('status', [0, 1]) 
>>>>>>> 6917bf9824fcb07844dae635e3605c42719024bb
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
<<<<<<< HEAD
                    'message' => 'Booking not found or does not belong to you.',
                ], 404);
            }

            // Optional: Restrict rescheduling within 24 hours
            $now = now();
            $originalDate = Carbon::parse($booking->session_date_start);
            if ($originalDate->diffInHours($now) < 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot reschedule within 24 hours of the session.',
                ], 403);
            }

            // Update the booking
            $booking->session_date_start = $request->new_date;
            $booking->session_date_end = $request->new_date;
            $booking->slot_time_start = $request->new_slot_time_start;
            $booking->slot_time_end = $request->new_slot_time_end ?? $request->new_slot_time_start;
            $booking->status = 2; // You can define '2' as 'Rescheduled' in your system
            $booking->save();

            // Optional: Notify user here via email or in-app notification

            return response()->json([
                'success' => true,
                'message' => 'Session rescheduled successfully.',
                'data' => $booking,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while rescheduling.',
                'error' => $e->getMessage(),
=======
                    'message' => 'Booking not found or cannot be rescheduled.',
                ], 404);
            }

             $conflict = BookingPackages::where('coach_id', $coach_id)
            ->where('id', '!=', $booking->id)
            ->where('session_date_start', $request->new_session_date_start)
            ->where('slot_time_start', $request->new_slot_time_start)
            ->whereIn('status', [0, 1]) 
            ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected time slot is already booked.',
                ], 409);
            }

            // Update the session date and slot
            $booking->session_date_start = $request->new_session_date_start;
            $booking->session_date_end = $request->new_session_date_start;
            $booking->slot_time_start = $request->new_slot_time_start;
            $booking->slot_time_end = $request->new_slot_time_start;
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking rescheduled successfully.',
                'data' => [
                    'booking_id' => $booking->id,
                    'new_date' => $booking->session_date_start,
                    'new_time' => $booking->slot_time_start,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during rescheduling.',
                'error' => $e->getMessage()
>>>>>>> 6917bf9824fcb07844dae635e3605c42719024bb
            ], 500);
        }
    }


<<<<<<< HEAD
=======
        public function UserConfirmedBooking(Request $request)
    {
        $user_id = Auth::id();
        $status = $request->input('status', 0); 

        try {
            $bookings = BookingPackages::with(['coach', 'coachPackage'])
                ->where('user_id', $user_id)
                ->when($status != 'all', function ($query) use ($status) { 
                    $query->where('status', $status);
                })
                ->whereHas('coach', function ($query) {
                    $query->where('user_type', 3)
                        ->where('email_verified', 1)
                        ->where('user_status', 1)
                        ->where('is_deleted', 0)
                        ->where('is_verified', 1);
                })
                ->whereHas('coachPackage', function ($query) {
                    $query->where('package_status', 1)
                        ->where('is_deleted', 0);
                })
                ->get();

            $grouped = $bookings->groupBy(function ($item) {
                return $item->session_date_start;
            })->map(function ($bookingsByDate) {
                return [
                    'date' => $bookingsByDate->first()->session_date_start,
                    'packages' => $bookingsByDate->groupBy('package_id')->map(function ($packageBookings) {
                        $firstBooking = $packageBookings->first();
                        $package = $firstBooking->coachPackage;

                        if (!$package) {                      
                            return null; 
                        }

                        return [
                            'package_id' => $package->id,
                            'title' => $package->title,
                            'coach_id' => $package->coach_id,
                            'users' => $packageBookings->map(function ($booking) {
                                $coach = $booking->coach;

                                if (!$coach) {                               
                                    return null;
                                }

                                return [
                                    'id' => $coach->id,
                                    'first_name' => $coach->first_name,
                                    'last_name' => $coach->last_name,
                                    'email' => $coach->email,
                                    'profile_image' => $coach->profile_image ? asset('public/uploads/profile_image/' . $coach->profile_image) : null,
                                    'slot_time_start' => $booking->slot_time_start,
                                    'status' => $booking->status,
                                    'booking_id' => $booking->id,
                                ];
                            })->filter()->values() 
                        ];
                    })->filter()->values() 
                ];
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Grouped booking Coach data by package with status filtering',
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

        public function ChangeBookingStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:booking_packages,id',
            'status' => 'required|in:0,1,2,3', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $booking = BookingPackages::find($request->booking_id);
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found.',
                ], 404);
            }

            if ($booking->coach_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to modify this booking.',
                ], 403);
            }

            $booking->status = $request->status;
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully.',
                'data' => [
                    'booking_id' => $booking->id,
                    'status' => $booking->status
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating the booking status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



>>>>>>> 6917bf9824fcb07844dae635e3605c42719024bb
    


}