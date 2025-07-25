<?php

namespace App\Http\Controllers\Api;

use App\Models\UserServicePackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'coaching_type'       => $request->coaching_type,
            'delivery_mode'       => $request->delivery_mode,
            'session_count'       => $request->session_count,
            'session_duration'    => $request->session_duration,
            'session_format'      => $request->session_format,
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
