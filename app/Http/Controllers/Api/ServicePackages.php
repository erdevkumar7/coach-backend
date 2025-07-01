<?php

namespace App\Http\Controllers\Api;
use App\Models\UserServicePackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

class ServicePackages extends Controller
{
    public function getAllUserServicePackage()
    {
        // $packages = DB::table('user_service_packages')
        //     ->get();
        // return response()->json($packages);

        $UserServicePackage = UserServicePackage::get();
        if ($UserServicePackage->isEmpty()) {
            return response()->json(['message' => 'No service package found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'All services package',
            'data' => $UserServicePackage
        ], 200);
    }

    public function getUserServicePackage(Request $request , $id)
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

        $UserServicePackage = UserServicePackage::where('id', $id)
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
        //return "sgsdgs";
        $validator = Validator::make($request->all(), [
            'coach_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'package_status' => 'required|in:0,1,2',
            'short_description' => 'nullable|string',
            'coaching_category' => 'nullable|integer',
            'description' => 'nullable|string',
            'focus' => 'nullable|string|max:255',
            'coaching_type' => 'nullable|integer',
            'delivery_mode' => 'nullable|string|max:100',
            'session_count' => 'nullable|integer',
            'session_duration' => 'nullable|string|max:50',
            'target_audience' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'booking_slot' => 'nullable|date',
            'booking_window' => 'nullable|string|max:100',
            'cancellation_policy' => 'nullable',
            'rescheduling_policy' => 'nullable|string|max:255',
            'media_file' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'nullable|in:draft,published',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // $img = $request->image;
        // $ext = $img->getClientOriginalExtension();
        // $imgName = time(). '.' . $ext;
        // $img->move(public_path().'/uploads',$imgName);

        if ($request->hasFile('media_file')) {
            $img = $request->file('media_file');
            $ext = $img->getClientOriginalExtension();
            $imgName = time() . '.' . $ext;
            $img->move(public_path('uploads'), $imgName);
        } else {
            return response()->json(['error' => 'Media file not uploaded.'], 400);
        }


        $package = UserServicePackage::create([
            'coach_id' => $request->coach_id,
            'title' => $request->title,
            'package_status' => $request->package_status,
            'short_description' => $request->short_description,
            'coaching_category' => $request->coaching_category,
            'description' => $request->description,
            'focus' => $request->focus,
            'coaching_type' => $request->coaching_type,
            'delivery_mode' => $request->delivery_mode,
            'session_count' => $request->session_count,
            'session_duration' => $request->session_duration,
            'target_audience' => $request->target_audience,
            'price' => $request->price,
            'currency' => $request->currency,
            'booking_slot' => $request->booking_slot,
            'booking_window' => $request->booking_window,
            'cancellation_policy' => $request->cancellation_policy,
            'rescheduling_policy' => $request->rescheduling_policy,
            'media_file' => $imgName,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Service package added successfully',
            'data' => $package
        ]);
    }












}
