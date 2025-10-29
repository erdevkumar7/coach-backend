<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\CoachingRequest;
use App\Models\BookingPackages;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HomeSetting;

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






    public function CoachplanStatus(Request $request)
    {
        $coachId = auth()->id();  

        $purchase = UserSubscription::where('user_id', $coachId)
                                    ->latest()  
                                    ->first();

        if (!$purchase) {
            return response()->json([
                'message' => 'no plan',
                'plan_status' => 0,
            ], 404);
        }

        $startDate = Carbon::parse($purchase->start_date);
        $endDate = Carbon::parse($purchase->end_date);

        $formattedStartDate = $startDate->format('d-m-Y');
        $formattedEndDate = $endDate->format('d-m-Y');

        $planData = [
            'id' => $purchase->id,
            'plan_id' => $purchase->plan_id,
            'plan_name' => $purchase->plan_name,
            'amount' => $purchase->amount,
            'plan_content' => $purchase->plan_content,
            'start_date' => $formattedStartDate,
            'end_date' => $formattedEndDate,
        ];

        if ($endDate->endOfDay() < now()->startOfDay()) {
            return response()->json([
                'message' => 'plan expired',
                'plan_status' => 0,
            ] + $planData);
        }

        return response()->json([
            'message' => 'plan valid',
            'plan_status' => 1,
        ] + $planData);
    }




    // public function getCoachSubcriptionPlan(Request $request)    
    // {
    //     // Retrieve all available plans
    //     $plans = Subscription::where('is_deleted', 0)  
    //                         ->where('is_active', 1)     
    //                         ->get();  

    //     // If no plans are found
    //     if ($plans->isEmpty()) {
    //         return response()->json(['message' => 'No plans available.'], 400);
    //     }

    //     // Successfully retrieved plans
    //     return response()->json([
    //         'message' => 'All plans retrieved successfully.',
    //         'plans' => $plans
    //     ], 200);
    // }

   

    public function getCoachSubcriptionPlan(Request $request)    
    {
        $plans = Subscription::where('is_deleted', 0)  
                            ->where('is_active', 1)   
                            ->where('plan_amount', '>', 0)   
                            ->get();  

        if ($plans->isEmpty()) {
            return response()->json(['message' => 'No plans available.'], 400);
        }

        $now = CarbonImmutable::now();

        $formattedPlans = $plans->map(function ($plan) use ($now) {
            $duration = (int) $plan->plan_duration;
            $unit = (int) $plan->duration_unit;

            $unitLabel = '';
            $unitName = '';
            $totalDays = 0;

            switch ($unit) {
                case 1: // Days
                    $unitLabel = $duration === 1 ? 'Day' : 'Days';
                    $unitName = 'Day';
                    $totalDays = $duration;
                    break;

                case 2: // Months
                    $unitLabel = $duration === 1 ? 'Month' : 'Months';
                    $unitName = 'Month';
                    $end = $now->addMonths($duration);
                    $totalDays = $now->diffInDays($end);
                    break;

                case 3: // Years
                    $unitLabel = $duration === 1 ? 'Year' : 'Years';
                    $unitName = 'Year';
                    $end = $now->addYears($duration);
                    $totalDays = $now->diffInDays($end);
                    break;
            }

            // Add custom fields
            $plan->formatted_duration = "{$duration} {$unitLabel}";
            $plan->duration_days = $totalDays;
            $plan->duration_unit_name = $unitName;

            return $plan;
        });

        return response()->json([
            'message' => 'All plans retrieved successfully.',
            'plans' => $formattedPlans
        ], 200);
    }

    //     public function CoachpaymentHistory(Request $request)
    // {
    //     $coachId = auth()->id();

    //     $payments = UserSubscription::where('user_id', $coachId)
    //                                 ->orderBy('created_at', 'desc')  
    //                                 ->get();

    //     if ($payments->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No payment history available.',
    //             'payments' => [],
    //         ]);
    //     }

    //     $paymentHistory = $payments->map(function ($payment) {
    //         $startDate = Carbon::parse($payment->start_date);
    //         $endDate = Carbon::parse($payment->end_date);

    //         return [
    //             'id' => $payment->id ?? 'N/A',
    //             'plan_id' => $payment->plan_id ?? 'N/A',
    //             'plan_name' => $payment->plan_name ?? 'N/A',
    //             'plan_content' => $payment->plan_content ?? 'N/A',
    //             'txn_id' => $payment->txn_id ?? 'N/A',
    //             'amount' => $payment->amount ?? 0,
    //             'start_date' => $startDate->format('d-m-Y'),
    //             'end_date' => $endDate->format('d-m-Y'),
    //             'payment_status' => 'Paid',
    //             'payment_date' => $payment->created_at->format('d-m-Y H:i:s'),
    //         ];
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'payments' => $paymentHistory,
    //     ]);
    // }

        public function CoachpaymentHistory(Request $request)
    {
        $coachId = auth()->id();

        // Check if a specific payment ID is requested
        $paymentId = $request->get('id');

        // If an ID is provided, fetch the specific payment; otherwise, get all payments
        if ($paymentId) {
            $payments = UserSubscription::where('user_id', $coachId)
                                        ->where('id', $paymentId)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        } else {
            $payments = UserSubscription::where('user_id', $coachId)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        }

        if ($payments->isEmpty()) {
            return response()->json([
                'message' => 'No payment history available.',
                'payments' => [],
            ]);
        }

        // Prepare payment data for the response
        $paymentHistory = $payments->map(function ($payment) {
            $startDate = Carbon::parse($payment->start_date);
            $endDate = Carbon::parse($payment->end_date);

            // Generate PDF for each payment
            $pdf = Pdf::loadView('pdf.coach_payment_history', compact('payment'));
            
            // Save the PDF to storage (using a unique name)
            $pdfPath = storage_path('app/public/pdfs/payment_history_' . $payment->id . '.pdf');
            $pdf->save($pdfPath);

            // Generate the URL to the saved PDF
            $pdfUrl = url('storage/pdfs/' . basename($pdfPath));

            return [
                'id' => $payment->id ?? 'N/A',
                'plan_id' => $payment->plan_id ?? 'N/A',
                'plan_name' => $payment->plan_name ?? 'N/A',
                'plan_content' => $payment->plan_content ?? 'N/A',
                'txn_id' => $payment->txn_id ?? 'N/A',
                'amount' => $payment->amount ?? 0,
                'start_date' => $startDate->format('d-m-Y'),
                'end_date' => $endDate->format('d-m-Y'),
                'payment_status' => 'Paid',
                'payment_date' => $payment->created_at->format('d-m-Y H:i:s'),
                'pdf_link' => $pdfUrl,  // Add the PDF link here
            ];
        });

        // Return payment history with the PDF link for each payment
        return response()->json([
            'success' => true,
            'payments' => $paymentHistory,
        ]);
    }

    public function getHomePageSection()
    {
        $sections = HomeSetting::select('section_name', 'title', 'subtitle', 'description', 'image')
                                ->get()
                                ->map(function ($section) {
                                    $section->image = $section->image ? asset('public/uploads/blog_files/' . $section->image): null;                                  
                                    return $section;
                                });

        if ($sections->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No home page sections found.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All home page sections retrieved successfully.',
            'data' => $sections,
        ], 200);
    }







    


}