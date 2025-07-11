<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
class SubscriptionPlanController extends Controller
{

    public function SubscriptionPlans()
    {

        try{
            // $packages = DB::table('user_service_packages')
            //     ->get();
            // return response()->json($packages);
            $SubscriptionPlans = Subscription::get();
            if ($SubscriptionPlans->isEmpty()) {
                return response()->json(['message' => 'No subscription plan found'], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'All subscription plans',
                'data' => $SubscriptionPlans
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
