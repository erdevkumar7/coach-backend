<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\CoachingRequest;
use App\Models\BookingPackages;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserDashboardController extends Controller
{
        public function UserRequestCount(Request $request)
    {

        try{
                $user = Auth::user();

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not authenticated.',
                    ], 401);
                }

                $count = CoachingRequest::where('user_id', $user->id) 
                    ->where('is_active', 1)
                    ->count();

                return response()->json([
                    'status' => true,
                    'message' => 'Coaching request count',
                    'count' => $count
                ]);

         } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating the booking status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function UserCoachingStatusCount(Request $request)
    {

        try{
                $user = Auth::user();

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not authenticated.',
                    ], 401);
                }

                $validated = $request->validate([
                    'status' => 'required|integer|in:0,1,2,3',
                ]);

                $statusLabels = [
                    0 => 'pending',
                    1 => 'confirmed',
                    2 => 'completed',
                    3 => 'canceled',
                ];

                $statusCode = $validated['status'];
                $statusLabel = $statusLabels[$statusCode];

                $count = BookingPackages::where('user_id', $user->id)
                    ->where('status', $statusCode)
                    ->whereHas('coach', function ($query) {
                        $query->where([
                            ['user_type', 3],
                            ['email_verified', 1],
                            ['user_status', 1],
                            ['is_deleted', 0],
                            ['is_verified', 1],
                        ]);
                    })
                    ->whereHas('coachPackage', function ($query) {
                        $query->where([
                            ['package_status', 1],
                            ['is_deleted', 0],
                        ]);
                    })
                    ->count();

                return response()->json([
                    'success' => true,
                    'message' => 'Coaching count for status: ' . $statusLabel,
                    'status' => $statusCode,
                    // 'status_label' => $statusLabel,
                    'count' => $count
                ]);
          } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating the booking status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
