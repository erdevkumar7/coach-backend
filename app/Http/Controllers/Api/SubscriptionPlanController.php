<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use DB;
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



    // public function SubscriptionPlansByDuration(Request $request)
    // {
    //     $type = strtolower($request->input('type', 'all'));

    //     $allowedTypes = ['all', 'daily', 'monthly', 'yearly'];

    //     if (!in_array($type, $allowedTypes)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid type provided. Allowed types: all, daily, monthly, yearly.',
    //         ], 400); 
    //     }

    //     $query = Subscription::where('is_active', 1)
    //         ->where('is_deleted', 0);

    //     $map = [
    //         'daily' => 1,
    //         'monthly' => 2,
    //         'yearly' => 3,
    //     ];

    //     if (isset($map[$type])) {
    //         $query->where('duration_unit', $map[$type]);
    //     }

    //     $plans = $query->get();

    //     return response()->json([
    //         'success' => true,
    //         'type' => $type,
    //         'plans' => $plans,
    //     ]);
    // }

        public function SubscriptionPlansByDuration(Request $request)
    {
        $type = strtolower($request->input('type', 'all'));

        $allowedTypes = ['all', 'daily', 'monthly', 'yearly'];

        if (!in_array($type, $allowedTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid type provided. Allowed types: all, daily, monthly, yearly.',
            ], 400);
        }

        $query = Subscription::where('is_active', 1)
            ->where('is_deleted', 0);

        $map = [
            'daily' => 1,
            'monthly' => 2,
            'yearly' => 3,
        ];

        if (isset($map[$type])) {
            $query->where('duration_unit', $map[$type]);
        }

        $plans = $query->get();

       foreach ($plans as $plan) {
        $plan->features = DB::table('subscription_features')
            ->where('subscription_id', $plan->id)
            ->select('id', 'feature_text')
            ->get();
        }
        return response()->json([
            'success' => true,
            'type' => $type,
            'plans' => $plans,
        ]);
    }


}
