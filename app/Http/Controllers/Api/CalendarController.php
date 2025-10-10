<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\CoachingRequest;
use App\Models\BookingPackages;
use App\Models\Subscription;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{

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





    public function bookingRescheduleByUser(Request $request)
    {
        $user_id = Auth::id();

        $validated = $request->validate([
            'booking_id' => 'required|exists:booking_packages,id',
            'status' => 'required',
            // 'session_date_start' => 'required|date',
            // 'slot_time_start' => 'required|date_format:H:i',
        ]);

        try {
            $booking = BookingPackages::where('id', $request->booking_id)
                ->where('user_id', $user_id)
                ->where('status', 3) 
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or cannot be rescheduled.',
                ], 404);
            }

             $conflict = BookingPackages::where('user_id', $user_id)
            ->where('id', '!=', $booking->id)
            ->where('session_date_start', $request->session_date_start)
            ->where('slot_time_start', $request->slot_time_start)
             ->whereIn('status', [0, 1, 2]) 
            ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected time slot is already booked.',
                ], 409);
            }

            $booking->session_date_start = $request->session_date_start;
            $booking->session_date_end = $request->session_date_start;
            $booking->slot_time_start = $request->slot_time_start;
            $booking->slot_time_end = $request->slot_time_start;
            $booking->status = $request->status;
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
            ], 500);
        }
    }


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

      public function checkExpiration(Request $request)
    {
        $coachId = auth()->id();  

        $purchase = Subscription::where('coach_id', $coachId)
                            ->latest()  
                            ->first();

        if (!$purchase) {
            return response()->json(['message' => 'no plan'], 400);
        }

        if ($purchase->expiration_date < now()) {
            return response()->json(['message' => 'plan expire'], 400);
        }

        return response()->json(['message' => 'plan success']);
    }

    public function getCoachSubcriptionPlan(Request $request)    
    {
        // Retrieve all available plans
        $plans = Subscription::where('is_deleted', 0)  
                            ->where('is_active', 1)     
                            ->get();  

        // If no plans are found
        if ($plans->isEmpty()) {
            return response()->json(['message' => 'No plans available.'], 400);
        }

        // Successfully retrieved plans
        return response()->json([
            'message' => 'All plans retrieved successfully.',
            'plans' => $plans
        ], 200);
    }




    


}