<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\master_price_model;

class MasterController extends Controller
{
    public function getmasterprices()
    {
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
    }
}
