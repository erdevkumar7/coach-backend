<?php

namespace App\Http\Controllers\Api;

use App\Models\UserServicePackage;
use App\Models\BookingPackages;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ServicePackages extends Controller
{

    public function getAllCoachServicePackage()
    {
        $coach = Auth::user(); //  JWT Authenticated User
        $UserServicePackage = UserServicePackage::with([
            'deliveryMode:id,mode_name',
            'sessionFormat:id,name,description',
            'priceModel:id,name,description',
        ])->where('is_deleted', 0)->where('coach_id', $coach->id)->orderby('created_at', 'desc')->get();

        if ($UserServicePackage->isEmpty()) {
            return response()->json(['message' => 'No service package found'], 404);
        }

        // Append media_url if media exists
        $UserServicePackage->transform(function ($package) {
            if ($package->media_file) {
                $package->media_file = url('public/uploads/service_packages/' . $package->media_file);
            } else {
                $package->media_file = null;
            }
            return $package;
        });

        return response()->json([
            'success' => true,
            'message' => 'All services package',
            'data' => $UserServicePackage,
        ], 200);
    }


    public function getUserServicePackage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'coach_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $coachId = $request->input('coach_id');
        // $UserServicePackage = UserServicePackage::with('user') // 👈 this loads the related user
        $UserServicePackage = UserServicePackage::with(['user' => function ($q) {
            $q->select([
                'id',
                'first_name',
                'last_name',
                'display_name',
                'email',
                'contact_number',
                'profile_image',
                'gender',
                'short_bio',
                'exp_and_achievement',
                'professional_title',
                'company_name',
                'professional_profile',
                'country_id',
                'state_id',
                'city_id',
                'user_type',
                'is_paid',
                'user_timezone',
                'user_status',
                'is_deleted',
                'is_corporate'
            ]);
        }])
            ->where('id', $id)
            ->where('coach_id', $coachId)
            ->first();

        if (!$UserServicePackage) {
            return response()->json([
                'status'  => false,
                'message' => 'User service package not found'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User service package found',
            'data'    => $UserServicePackage
        ], 200);
    }

    public function addServicePackage(Request $request)
    {
        // print_r($request->all());die;
        $coach = Auth::user(); //  JWT Authenticated User

        if (!$coach) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }
        // Handle media file upload
        $mediaFile = null;
        $originalFilename = null;
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $originalFilename = $file->getClientOriginalName();
            $mediaFile = time() . '_' . $originalFilename;
            // Step 1: Delete the old file if it exists
            if ($request->media_file_name) {
                $oldPath = public_path('uploads/service_packages/' . $request->media_file_name);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            // Step 2: Move the new file
            $file->move(public_path('uploads/service_packages'), $mediaFile);
        }

        $data = [
            'coach_id'            => $coach->id,
            'title'               => $request->title,
            'short_description'   => $request->short_description,
            'coaching_category'   => $request->coaching_category,
            'description'         => $request->description,
            'focus'               => $request->focus,
            // 'coaching_type'       => $request->coaching_type,
            'booking_availability_start' => $request->booking_availability_start,
            'booking_availability_end' => $request->booking_availability_end,
            'session_duration'    => $request->session_duration,
            'delivery_mode'    => $request->delivery_mode,
            'delivery_mode_detail'    => $request->delivery_mode_detail,
            'session_format'      => $request->session_format,
            'session_count'      => $request->session_count,
            'package_status'      => $request->package_status,
            'age_group'           => $request->age_group,
            'price'               => $request->price,
            'price_model'         => $request->price_model,
            'currency'            => $request->currency,
            'booking_slots'        => $request->booking_slots,
            // 'booking_availability' => $request->booking_availability,
            'booking_window'      => $request->booking_window,
            'cancellation_policy' => $request->cancellation_policy,
            'rescheduling_policy' => $request->rescheduling_policy,
            'media_file'          => $mediaFile ?? ($package->media_file ?? null),
            'media_original_name' => $originalFilename ?? ($package->media_original_name ?? null),
            'booking_slots'       => $request->booking_slots,
            'communication_channel'  => $request->communication_channel,
            'booking_window_start'  => $request->booking_window_start,
            'booking_window_end'  => $request->booking_window_end,
            'booking_time'  => $request->booking_time,
        ];


        $package = UserServicePackage::create($data);
        return response()->json([
            'status' => true,
            'message' => 'Service package added successfully',
            'data' => $package
        ]);
    }

    public function updateServicePackage(Request $request)
    {
        try {
            $coach = Auth::user(); // JWT Authenticated User

            if (!$coach) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            // 2️⃣ Validate request data
            $validated = $request->validate([
                'package_id' => 'required|integer|exists:user_service_packages,id',
            ]);


            // Validate package ID
            $package = UserServicePackage::where('id', $validated['package_id'])
                ->where('coach_id', $coach->id)
                ->first();

            if (!$package) {
                return response()->json([
                    'status' => false,
                    'message' => 'Service package not found of this coach',
                ], 404);
            }

            // Handle media file upload
            $mediaFile = $package->media_file;
            $originalFilename = $package->media_original_name;

            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $originalFilename = $file->getClientOriginalName();
                $mediaFile = time() . '_' . $originalFilename;

                // Delete old file if exists
                $oldPath = public_path('uploads/service_packages/' . $package->media_file);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }

                // Move new file
                $file->move(public_path('uploads/service_packages'), $mediaFile);
            }

            // Update fields
            $package->update([
                'title'               => $request->title,
                'short_description'   => $request->short_description,
                'coaching_category'   => $request->coaching_category,
                'description'         => $request->description,
                'focus'               => $request->focus,
                'booking_availability_start' => $request->booking_availability_start,
                'booking_availability_end'   => $request->booking_availability_end,
                'session_duration'    => $request->session_duration,
                'delivery_mode'       => $request->delivery_mode,
                'delivery_mode_detail' => $request->delivery_mode_detail,
                'session_format'      => $request->session_format,
                'session_count'       => $request->session_count,
                'package_status'      => $request->package_status,
                'age_group'           => $request->age_group,
                'price'               => $request->price,
                'price_model'         => $request->price_model,
                'booking_availability' => $request->booking_availability,
                'currency'            => $request->currency,
                'booking_slots'       => $request->booking_slots,
                'booking_window'      => $request->booking_window,
                'cancellation_policy' => $request->cancellation_policy,
                'rescheduling_policy' => $request->rescheduling_policy,
                'communication_channel' => $request->communication_channel,
                'booking_window_start'  => $request->booking_window_start,
                'booking_window_end'    => $request->booking_window_end,
                'booking_time'          => $request->booking_time,
                'media_file'            => $mediaFile,
                'media_original_name'   => $originalFilename,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Service package updated successfully.',
                'data' => $package
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deletePackageRequest(Request $request)
    {
        try {
            $user = Auth::user();

            // 1️⃣ Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            // 2️⃣ Validate request data
            $validated = $request->validate([
                'package_id' => 'required|integer|exists:user_service_packages,id',
            ]);

            // 3️⃣ Find the package
            $package = UserServicePackage::where('id', $validated['package_id'])
                ->where('coach_id', $user->id) // ensure the package belongs to this user
                ->first();

            if (!$package) {
                return response()->json([
                    'status' => false,
                    'message' => 'Package not found or unauthorized to delete.',
                ], 404);
            }

            // 4️⃣ Delete the package
            $package->delete();

            // 5️⃣ Return success response
            return response()->json([
                'status' => true,
                'message' => 'Package deleted successfully.',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function getServicePackageById($coach_id, $package_id)
    {

        $UserServicePackage = UserServicePackage::with([
            'user:id,first_name,last_name,profile_image,detailed_bio',
            'ageGroup:id,age_range,group_name',
            'coachingCategory:id,category_name',
            'deliveryMode:id,mode_name',
            'sessionFormat:id,name,description',
            'priceModel:id,name,description',
        ])->where('id', $package_id)
            ->where('coach_id', $coach_id)
            ->where('is_deleted', 0)
            ->get();

        // Append media_url if media exists
        $UserServicePackage->transform(function ($package) {
            if ($package->media_file) {
                $package->media_file = url('public/uploads/service_packages/' . $package->media_file);
            } else {
                $package->media_file = null;
            }
            //user profile_image
            if ($package->user->profile_image) {
                $package->user->profile_image = url('public/uploads/profile_image/' . $package->user->profile_image);
            } else {
                $package->user->profile_image = null;
            }
            return $package;
        });


        return response()->json([
            'success' => true,
            'data'    => $UserServicePackage,
        ]);
    }

    public function date_time_avalibility(Request $request)
    {
        try {
            $userPackage = UserServicePackage::with('user', 'deliveryMode', 'priceModel')
                ->where('id', $request->package_id)
                ->first();

            if (!$userPackage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found.',
                ], 404);
            }

            $data = [
                "coach_profile" => [
                    'package_id' => $userPackage->id,
                    'coach_id' => $userPackage->coach_id,
                    'first_name' => $userPackage->user->first_name,
                    'last_name' => $userPackage->user->last_name,
                    'package_title' => $userPackage->title,
                    'profile_image' => $userPackage->user->profile_image
                        ? url('public/uploads/profile_image/' . $userPackage->user->profile_image)
                        : '',
                    'session_title' => $userPackage->title,
                    'session_price' => $userPackage->price,
                    'booking_time' => $userPackage->booking_time,
                    'delivery_mode_detail' => $userPackage->delivery_mode_detail,
                    'delivery_mode' => $userPackage->deliveryMode->mode_name,
                    'price_model' => $userPackage->priceModel->name,
                    'currency' => $userPackage->currency,
                    'short_description' => $userPackage->short_description,
                    'session_duration' => $userPackage->session_duration,
                    'session_count' => $userPackage->session_count,
                    'cancellation_policy' => $userPackage->cancellation_policy,
                    'rescheduling_policy' => $userPackage->rescheduling_policy,
                    'booking_availability_start' => $userPackage->booking_availability_start,
                ]
            ];

            // Dates range
            $startDate = Carbon::parse($userPackage->booking_availability_start);
            $endDate   = Carbon::parse($userPackage->booking_availability_end);
            $today     = Carbon::today();

            $period = CarbonPeriod::create($startDate, $endDate);
            $dates = [];

            $durationInMinutes = (int) filter_var($userPackage->session_duration, FILTER_SANITIZE_NUMBER_INT);
            $sessionCount      = (int) $userPackage->booking_slots;
            // $startTime         = Carbon::parse($userPackage->booking_availability_start)->format('H:i');
            $startTime         = Carbon::parse($userPackage->booking_time)->format('H:i');

            foreach ($period as $date) {
                if ($date->greaterThanOrEqualTo($today)) {

                    // Fetch booked slots considering session_date_end also
                    $bookedSlots = BookingPackages::where('package_id', $userPackage->id)
                        ->whereDate('session_date_start', '<=', $date->format('Y-m-d'))
                        ->whereDate('session_date_end', '>=', $date->format('Y-m-d'))
                        ->pluck('slot_time_start')
                        ->map(function ($time) {
                            return Carbon::parse($time)->format('H:i');
                        })
                        ->toArray();

                    // print_r($bookedSlots);die;
                    // Generate all possible slots
                    $availableSlots = [];
                    $slotTime = Carbon::createFromFormat('H:i', $startTime);

                    for ($i = 0; $i < $sessionCount; $i++) {
                        $timeStr = $slotTime->format('H:i');
                        if (!in_array($timeStr, $bookedSlots)) {
                            $availableSlots[] = $timeStr;
                        }
                        $slotTime->addMinutes($durationInMinutes);
                    }

                    if (!empty($availableSlots)) {
                        $dates[] = [
                            'date' => $date->format('Y-m-d'),
                            'available_times' => $availableSlots
                        ];
                    }
                }
            }

            $data['availability'] = $dates;

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function getAarrayOfServicePackageIdsByCoachId($coach_id)
    {
        $arrayOfpackageIDs = UserServicePackage::where('coach_id', $coach_id)
            ->orderBy('id') // or created_at, or custom sort logic
            ->pluck('id');

        return response()->json([
            'success' => true,
            'data'    => $arrayOfpackageIDs,
        ]);
    }

    public function transaction_detail(Request $request)
    {
        try {
            $user = Auth::user(); // Authenticated user
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 403);
            }

            $txn_id = $request->txn_id;

            $transactionDetail = Transaction::with([
                'coachPackages',
                'coachPackages.user',
                'coachPackages.priceModel',
                'coachPackages.deliveryMode',
                'coachPackages.pcancellation_policy'
            ])
                ->where('payment_id', $txn_id)
                ->first();

            if (!$transactionDetail || !$transactionDetail->coachPackages) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found.',
                ], 404);
            }

            $package = $transactionDetail->coachPackages;
            $coach   = $package->user;
            $cancel   = $package->pcancellation_policy;

            $data = [
                "transaction_detail" => [
                    'transaction_id' => $transactionDetail->id,
                    'package_id'     => $package->id,
                    'coach_id'       => $package->coach_id,
                    'first_name'     => $coach->first_name ?? '',
                    'last_name'      => $coach->last_name ?? '',
                    'user_first_name' => $user->first_name ?? '',
                    'user_last_name' => $user->last_name ?? '',
                    'package_title'  => $package->title,
                    'profile_image'  => $coach->profile_image
                        ? url('public/uploads/profile_image/' . $coach->profile_image)
                        : '',
                    'session_title'  => $package->title,
                    'session_price'  => $package->price,
                    'booking_time'   => $transactionDetail->created_at,
                    'delivery_mode_detail' => $package->delivery_mode_detail,
                    'delivery_mode'  => $package->deliveryMode->mode_name ?? '',
                    'price_model'    => $package->priceModel->name ?? '',
                    'currency'       => $package->currency,
                    'short_description' => $package->short_description,
                    'session_duration'   => $package->session_duration,
                    'session_count'      => $package->session_count,
                    'cancellation_policy' => $cancel->name ?? '',
                    'rescheduling_policy' => $package->rescheduling_policy,
                    'booking_availability_start' => $package->booking_availability_start,
                ]
            ];

            return response()->json([
                'success' => true,
                'message' =>  "Transaction Detail",
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
