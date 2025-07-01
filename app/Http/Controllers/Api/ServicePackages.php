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










}
