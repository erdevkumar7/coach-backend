<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingPackages;
use App\Models\CoachingRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserController extends Controller
{
    public function updateProfileImage(Request $request)
    {
        try {
            $user = Auth::user(); //  JWT Authenticated User
            $id = $user->id;

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive.',
                ], 403);
            }

            $user = User::where('id', $id)
                ->where('user_status', 1)
                ->where('user_type', $request->user_type)
                ->first();

            if (!$request->hasFile('profile_image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided.'
                ], 400);
            }

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/profile_image'), $imageName);
                $user->profile_image = $imageName;
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully.',
                'profile_image'  => $user->profile_image
                    ? url('public/uploads/profile_image/' . $user->profile_image)
                    : '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token not valid or other error.',
                'error'   => $e->getMessage()
            ], 401);
        }
    }

    public function coachDashboard(Request $request)
{
    $user = Auth::user();
    $id   = $user->id;

    try {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $completedPackages = BookingPackages::where('coach_id', $id)
            ->whereRaw("STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s') < ?", [$now])
            ->count();


        $confirmedOrders = BookingPackages::where('coach_id', $id)
            ->whereRaw("STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s') > ?", [$now])
            ->count();


        $inProgressOrders = BookingPackages::with([
                'user.country',
                'user.userProfessional.coachType',
                'coachPackage',
            ])
            ->where('coach_id', $id)
            ->whereRaw("? BETWEEN STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s')
                           AND STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s')", [$now])
            ->get();

        $inProgressCount = $inProgressOrders->count();


        $upcomingBookings = BookingPackages::with([
                'user.country',
                'user.userProfessional.coachType',
                'coachPackage',
            ])
            ->where('coach_id', $id)
            ->whereRaw("STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s') > ?", [$now])
            ->limit(3)
            ->get();

        $newCoachingRequest = CoachingRequest::where('coach_id', $id)->count();


        $totalEarning = BookingPackages::where('coach_id', $id)->sum('amount');


        $upcomingResults = $upcomingBookings->map(function ($req) {
            $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);

            return [
                'booking_id'        => $req->id,
                'first_name'        => $req->user->first_name ?? null,
                'last_name'         => $req->user->last_name ?? null,
                'display_name'      => $req->user->display_name ?? null,
                'user_type'         => $req->user->user_type ?? null,
                'package_title'     => $req->coachPackage->title ?? null,
                'profile_image'     => $req->user->profile_image
                    ? url('public/uploads/profile_image/' . $req->user->profile_image)
                    : '',
                'session_date_start' => $req->session_date_start,
                'slot_time_start'    => $req->slot_time_start,
                'session_date_end'   => $req->session_date_end,
                'slot_time_end'      => $req->slot_time_end,
                'country'            => $req->user->country->country_name ?? null,
                'status'             => 'confirmed',
                // 'starts_in_minutes'  => $startDateTime->isFuture()
                //     ? Carbon::now()->diffInMinutes($startDateTime)
                //     : 0,
            ];
        });

        return response()->json([
            'status'  => true,
            'message' => 'Dashboard data fetched successfully',
            'data'    => [
                'completed_count'     => $completedPackages,
                'confirmed_count'     => $confirmedOrders,
                'in_progress_count'   => $inProgressCount,
                'new_requests'        => $newCoachingRequest,
                'total_earning'       => $totalEarning,
                // 'in_progress_bookings'=> $inProgressResults,
                'upcoming_bookings'   => $upcomingResults,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while fetching data.',
            'error'   => $e->getMessage()
        ], 500);
    }
}

public function coachServicePerformances(Request $request)
{

    try {

        return $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        if ($user->user_type != 2 || $user->is_deleted != 0 || $user->is_verified != 1 || $user->user_status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        return $id = $user->id;

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while fetching data.',
            'error'   => $e->getMessage()
        ], 500);
    }
}



        public function userDashboard78(Request $request)
    {
         $user = Auth::user();
         $id = $user->id;
        // echo $id;die;
        try{
         $now = Carbon::now();

       $completedPackages = BookingPackages::where('coach_id', $id)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s') < ?
                ", [$now])
                ->orderBy('booking_packages.id', 'desc')
                ->count();

       $confirmedOrders = BookingPackages::where('coach_id', $id)
                ->whereRaw("STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s') > ?", [$now])
                ->count();

       $inProgressOrders = BookingPackages::with([
                        'user.country',
                        'user.userProfessional.coachType',
                        'coachPackage',])
                    ->where('coach_id', $id)
                    ->whereRaw("? BETWEEN STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s')
                     AND STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s')", [$now])
                     ->get();
          $countshow = $inProgressOrders->count();
        //   print_r($inProgressOrders);die;
        $newCoachingRequest = CoachingRequest::where('coach_id',$id)->count();

        $totalEarning = BookingPackages::where('coach_id',$id)->sum('amount');

                    //  echo $inProgressOrders;die;



        $results = $inProgressOrders->getCollection()->map(function ($req) use ($relation, $now) {
        $show_relation = $relation;


        // $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);
        // $endDateTime   = Carbon::parse($req->session_date_end . ' ' . $req->slot_time_end);
        // $endDate       = Carbon::parse($req->session_date_end)->endOfDay();


        // $status = null;
        // if ($now->between($startDateTime, $endDateTime)) {
        //     $status = 'in-progress';
        // } elseif ($now->lt($startDateTime)) {
        //     $status = 'confirmed';
        // }

        // // Sessions left
        // $sessionLeft = $now->lte($endDate)
        //     ? $now->diffInDays($endDate)
        //     : 0;

        return [
            'id'                => $req->$show_relation->id ?? null,
            'booking_id'        => $req->id ?? null,
            'first_name'        => $req->$show_relation->first_name ?? null,
            'last_name'         => $req->$show_relation->last_name ?? null,
            'user_type'         => $req->$show_relation->user_type ?? null,
            'display_name'      => $req->$show_relation->display_name ?? null,
            'package_title'     => $req->coachPackage->title ?? null,
            'profile_image'     => $req->$show_relation->profile_image
                ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                : '',
            'session_date_start' => $req->session_date_start ?? null,
            'slot_time_start'    => $req->slot_time_start ?? null,
            'session_date_end'   => $req->session_date_end ?? null,
            'slot_time_end'      => $req->slot_time_end ?? null,
            'country'            => $req->$show_relation->country->country_name ?? null,
            'status'             => $status ?? null,
            'session_left'       => $status
                ? ($status === 'confirmed'
                    ? 'session not started yet'
                    : max(round($sessionLeft, 0) - 1, 0))
                : null,
            // 'created_at'         => $req->created_at ?? null,
            // 'updated_at'         => $req->updated_at ?? null,
        ];
    });
            return response()->json([
                'status' => true,
                'message' => 'Support request added successfully',
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
