<?php

namespace App\Http\Controllers\Api;

use App\Models\UserServicePackage;
use App\Models\BookingPackages;
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

    public function addUserServicePackage(Request $request)
    {
        $coach = Auth::user(); //  JWT Authenticated User

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
            'age_group'           => $request->age_group,
            'price'               => $request->price,
            'price_model'         => $request->price_model,
            'currency'            => $request->currency,
            'booking_slots'        => $request->booking_slots,
            'booking_availability' => $request->booking_availability,
            'booking_window'      => $request->booking_window,
            'cancellation_policy' => $request->cancellation_policy,
            'rescheduling_policy' => $request->rescheduling_policy,
            'media_file'          => $mediaFile ?? ($package->media_file ?? null),
            'media_original_name' => $originalFilename ?? ($package->media_original_name ?? null),
            'booking_slots'       => $request->booking_slots,
        ];


        $package = UserServicePackage::create($data);
        return response()->json([
            'status' => true,
            'message' => 'Service package added successfully',
            'data' => $package
        ]);
    }
    
    public function getServicePackageById($coach_id, $package_id)
    {

        $UserServicePackage = UserServicePackage::with([
            'user:id,first_name,last_name,profile_image,short_bio',
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
        $userPackage = UserServicePackage::with('user', 'deliveryMode','priceModel')
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
                'profile_image' => $userPackage->user->profile_image
                    ? url('public/uploads/profile_image/' . $userPackage->user->profile_image)
                    : '',
                'session_title' => $userPackage->title,
                'session_price' => $userPackage->price,
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
        $startTime         = Carbon::parse($userPackage->booking_availability_start)->format('H:i');

        foreach ($period as $date) {
            if ($date->greaterThanOrEqualTo($today)) {

                // Fetch booked slots considering session_date_end also
                $bookedSlots = BookingPackages::where('package_id', $userPackage->id)
                    ->whereDate('session_date_start', '<=', $date->format('Y-m-d'))
                    ->whereDate('session_date_end', '>=', $date->format('Y-m-d'))
                    ->pluck('slot_time_start')
                    ->map(function($time) {
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
}