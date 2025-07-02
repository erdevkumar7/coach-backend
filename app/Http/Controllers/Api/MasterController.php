<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\master_price_model;
use App\Models\master_session_format;
use App\Models\master_cancellation_policy;
use App\Models\Blog;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class MasterController extends Controller
{

    public function GetMasterPrices()
    {

        try{
            // $packages = DB::table('user_service_packages')
            //     ->get();
            // return response()->json($packages);
            $master_price_model = master_price_model::get();
            if ($master_price_model->isEmpty()) {
                return response()->json(['message' => 'No master price found'], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'All master prices',
                'data' => $master_price_model
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function GetMasterSessionFormats()
    {

        $master_session_format = master_session_format::get();
        if ($master_session_format->isEmpty()) {
            return response()->json(['message' => 'No master session format found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'All master session format',
            'data' => $master_session_format
        ], 200);
    }

    public function GetMasterCancellationPolicies()
    {

        $get_master_cancellation_policies = master_cancellation_policy::get();
        if ($get_master_cancellation_policies->isEmpty()) {
            return response()->json(['message' => 'No master session format found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'All master session format',
            'data' => $get_master_cancellation_policies
        ], 200);
    }

    public function GetMasterBlogs()
    {
        try{
            $get_master_blogs = Blog::get();
            if ($get_master_blogs->isEmpty()) {
                return response()->json(['message' => 'No master blogs found'], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'All master blogs',
                'data' => $get_master_blogs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
