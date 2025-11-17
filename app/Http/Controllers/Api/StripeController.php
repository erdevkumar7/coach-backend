<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingPackages;
use App\Models\Message;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserServicePackage;
use App\Models\UserSubscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\PaymentIntent;
use Stripe\Stripe;


class StripeController extends Controller
{


    public function payServicePackages(Request $request)
    {
        try {
            set_time_limit(300);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $coachPackage = UserServicePackage::find($request->package_id);
            if (!$coachPackage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found.',
                ]);
            }

            $currency = $coachPackage->currency ?? 'usd';

            // Stripe requires amount in cents (except some currencies like XOF, JPY, etc.)
            if (in_array(strtoupper($currency), ['XOF', 'JPY'])) {
                $amountInCents = intval($total_amount);
            } else {
                $amountInCents = intval($coachPackage->price * 100);
            }

            $slotDateTimeString = json_encode($request->slot_date_time);
            // echo $amountInCents;die;
            // Create Checkout Session
            $session = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $coachPackage->title ?? 'Service Package',
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => [
                    'metadata' => [
                        'user_id'    => $user->id,
                        'coach_id'   => $coachPackage->coach_id,
                        'package_id' => $request->package_id,
                        'slot_date_time' => $slotDateTimeString,
                        'amount'     => $coachPackage->price,
                    ],
                ],
                'success_url' => url('/api/stripe/packages/success/{CHECKOUT_SESSION_ID}'),
                'cancel_url'  => url('/api/stripe/packages/cancel'),
            ]);


            return response()->json([
                'success'      => true,
                'redirect_url' => $session->url,
                'session_id'   => $session->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function userPackageSuccess($session_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Retrieve session + payment intent
            $session = CheckoutSession::retrieve($session_id);
            $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
            $metadata = $paymentIntent->metadata;

            if ($paymentIntent->status === 'succeeded') {

                // Save transaction
                $charge = null;
                if (!empty($paymentIntent->charges) && !empty($paymentIntent->charges->data)) {
                    $charge = $paymentIntent->charges->data[0];
                }
                $transaction =  Transaction::create([
                    'user_id'      => $metadata->user_id,
                    'coach_id'     => $metadata->coach_id,
                    'package_id'   => $metadata->package_id,
                    'booking_name' => $coachPackage->title ?? 'Service Package',
                    'amount'       => $metadata->amount,
                    'currency'     => $metadata->currency ?? 'usd',
                    'status'       => $paymentIntent->status,
                    'payment_id'    => $paymentIntent->id,
                    'responce_text' => "No response text available",
                    'payment_method_id' => $paymentIntent->payment_method,
                    'txn_id'       => $charge ? $charge->id : null,
                    'txn_date'     => $charge ? Carbon::createFromTimestamp($charge->created)->toDateTimeString() : now(),
                ]);

                $transactionId = $transaction->id;
                // Decode slot date & time JSON (from metadata)
                $slotDateTime = json_decode($metadata->slot_date_time, true);
                $coachPackage = UserServicePackage::find($metadata->package_id);
                $savedSlots = [];

                if (!empty($slotDateTime) && is_array($slotDateTime)) {
                    foreach ($slotDateTime as $slot) {
                        $session_date_start = $slot[0] ?? null; // date
                        $slot_time_start    = $slot[1] ?? null; // time

                        if (!$session_date_start || !$slot_time_start) {
                            continue; // skip invalid
                        }

                        $startDateTime = \Carbon\Carbon::parse($session_date_start . ' ' . $slot_time_start);
                        $endDateTime   = (clone $startDateTime)->addMinutes($coachPackage->session_duration_minutes);

                        $booking = new BookingPackages();
                        $booking->package_id         = $metadata->package_id;
                        $booking->coach_id           = $metadata->coach_id;
                        $booking->txn_id             = $transactionId;
                        $booking->user_id            = $metadata->user_id;
                        $booking->session_date_start = $session_date_start;
                        $booking->session_date_end   = $session_date_start;
                        $booking->slot_time_start    = $slot_time_start;
                        $booking->slot_time_end      = $endDateTime->format('H:i');
                        $booking->amount             = $metadata->amount;
                        $booking->delivery_mode      = $coachPackage->delivery_mode ?? null;
                        $booking->save();

                        $savedSlots[] = $booking->id;
                    }
                }


                // Send message after booking start

                $package_name = $coachPackage->title ?? 'Service Package';
                $document = $coachPackage->delivery_mode_detail ?? 'Document';

                // Fetch coach name
                $coach = User::select('first_name', 'last_name')->find($metadata->coach_id);
                $coach_name = $coach ? ($coach->first_name . ' ' . $coach->last_name) : 'Coach';

                // Format session date & time
                $date = Carbon::parse($booking->session_date_start)->format('l, F j'); // e.g., Saturday, July 13
                $start_time = Carbon::parse($booking->slot_time_start)->format('g:i A'); // e.g., 10:00 AM
                // $end_time = Carbon::parse($booking->slot_time_end)->format('g:i A');     // e.g., 11:00 AM

                // Final message
                $message_content = "<h6>{$package_name} Start Package With {$coach_name}</h6><p>{$date}, {$start_time} (GMT+8)</p>";

                Message::create([
                    'sender_id'    => $metadata->user_id,
                    'receiver_id'  => $metadata->coach_id,
                    'message'      => $message_content,
                    'document'      => $document,
                    'document_type'     => "link",
                    'is_read'      => 0,
                    'message_type' => 3,
                ]);

                // Send message after booking end



                $redirectUrl = env('FRONTEND_URL') . '/user/booking/confirm';

                return redirect()->away($redirectUrl . '?' . 'txn_id=' . $paymentIntent->id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed: ' . $paymentIntent->status,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ]);
        }
    }



    public function PayCoachSubcriptionPlan(Request $request)
    {
        try {
            set_time_limit(300);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            // $coachPackage = Subscription::find($request->plan_id);

            $coachPackage = Subscription::where('is_deleted', 0)
                ->where('id', $request->plan_id)
                ->where('is_active', 1)
                ->first();

            if (!$coachPackage) {
                return response()->json([
                    'success' => false,
                    'message' => 'plan not found.',
                ]);
            }

            // if ($coachPackage->duration_unit == 1) {
            //     $expirationDate = now()->addDays($coachPackage->plan_duration);
            // } elseif ($coachPackage->duration_unit == 2) {
            //     $expirationDate = now()->addMonths($coachPackage->plan_duration);
            // } elseif ($coachPackage->duration_unit == 3) {
            //     $expirationDate = now()->addYears($coachPackage->plan_duration);
            // }

            $startDate = Carbon::now();
            if ($coachPackage->duration_unit == 1) {
                $duration_unit='Daily';
                $expirationDate = $startDate->copy()->addDays($coachPackage->plan_duration);
            } elseif ($coachPackage->duration_unit == 2) {
                $duration_unit='Monthly';
                $expirationDate = $startDate->copy()->addMonths($coachPackage->plan_duration);
            } elseif ($coachPackage->duration_unit == 3) {
                $duration_unit='Yearly';
                $expirationDate = $startDate->copy()->addYears($coachPackage->plan_duration);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid duration unit.',
                ]);
            }


            $session = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $coachPackage->plan_name ?? 'Coach Package',
                        ],
                        'unit_amount' => $coachPackage->plan_amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => [
                    'metadata' => [
                        'user_id'    => $user->id,
                        'coach_name'    => $user->first_name . ' ' . $user->last_name,
                        'plan_id' => $request->plan_id,
                        'start_date' =>  now(),
                        'end_date' =>  $expirationDate,
                        'amount'     => $coachPackage->plan_amount,
                        'plan_name'     => $coachPackage->plan_name,
                        'duration_unit'     => $duration_unit,
                        'plan_content'     => $coachPackage->plan_content,
                    ],
                ],
                'success_url' => url('/api/stripe/Coachpackages/success/{CHECKOUT_SESSION_ID}'),
                'cancel_url'  => url('/api/stripe/packages/cancel'),
            ]);


            return response()->json([
                'success'      => true,
                'redirect_url' => $session->url,
                'session_id'   => $session->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function CoachPackageSuccess($session_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = CheckoutSession::retrieve($session_id);
            $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
            $metadata = $paymentIntent->metadata;

            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentIntent->payment_method);
            $paymentType = $paymentMethod->card->brand;
            $last4 = $paymentMethod->card->last4;
            $paymentMethodType = $paymentMethod->type;

            if ($paymentIntent->status === 'succeeded') {

                $UserSubscription =  UserSubscription::create([
                    'user_id'      => $metadata->user_id,
                    'coach_name'      => $metadata->coach_name,
                    'plan_id'   => $metadata->plan_id,
                    'amount'       => $metadata->amount,
                    'plan_name'       => $metadata->plan_name,
                    'duration_unit'       => $metadata->duration_unit,
                    'plan_content'       => $metadata->plan_content,
                    'start_date'       => $metadata->start_date,
                    'end_date'       => $metadata->end_date,
                    'txn_id'    => $paymentIntent->id,
                    'payment_method' => $paymentMethodType,
                    'payment_type'   => $paymentType,
                    'payment_last4'   => $last4,

                ]);

                $paymentHistory = DB::table('user_subscription')
                    ->where('id', $UserSubscription->id)
                    ->first();

                $pdf = Pdf::loadView('pdf.coach_payment_history', [
                    'paymentHistory' => $paymentHistory,
                ]);


                $folderPath = public_path('pdf/coach_payment_history');

                if (!file_exists($folderPath)) {

                    mkdir($folderPath, 0777, true);
                }


                $pdfFileName = 'coach_payment_history_' . $UserSubscription->id . '.pdf';

                $pdfFullPath = $folderPath . '/' . $pdfFileName;

                file_put_contents($pdfFullPath, $pdf->output());

                $pdfUrl = asset('pdf/coach_payment_history_/' . $pdfFileName);



                // dd('ok');

                $redirectUrl = env('FRONTEND_URL') . '/coach/subscription-plan';

                return redirect()->away($redirectUrl . '?' . 'txn_id=' . $paymentIntent->id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed: ' . $paymentIntent->status,
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ]);
        }
    }
}
