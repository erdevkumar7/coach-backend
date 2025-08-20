<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\User;
use App\Models\CoachingRequest;
use App\Models\BookingPackages;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class SimilarCoachesController extends Controller
{
    public function SimilarCoaches(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'coach_id'  => 'required',
        ]);


        $coachId = $request->input('coach_id');

        // $user_detail = Professional::where('user_id', $coachId)->first();
        // if (!$user_detail) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'User professional details not found',
        //     ], 404);
        // }

        // $coach_type = $user_detail->coach_type;

        // $similarCoaches = Professional::with('user')
        //     ->where('coach_type', $coach_type)
        //     ->where('user_id', '!=', $coachId)
        //     ->limit(5)
        //     ->get();

        $currentCoach = User::with('userProfessional')->find($coachId);
        $coachTypeId = $currentCoach->userProfessional->coach_type ?? null;

        $similarCoaches = User::with(['services.servicename'])
            ->where('id', '!=', $currentCoach->id)
            ->where('user_status', 1)
            ->where('user_type', 3)
            ->whereHas('userProfessional', function ($q) use ($coachTypeId) {
                $q->where('coach_type', $coachTypeId);
            })
            ->limit(5)
            ->get();


        $similarCoaches = $similarCoaches->map(function ($coach) {
            return [
                'id' => $coach->id,
                'first_name' => $coach->first_name,
                'last_name' => $coach->last_name,
                'professional_title' => $coach->professional_title,
                'company_name' => $coach->company_name,
                'profile_image' => $coach->profile_image
                    ? url('public/uploads/profile_image/' . $coach->profile_image)
                    : '',
                'service_names' => $coach->services->pluck('servicename.service')->filter()->values(),
            ];
        });


        if ($similarCoaches->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No similar coaches found',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Similar coaches data list',
            'data' => $similarCoaches
        ]);
    }
public function getPendingCoaching(Request $request)
{
    $user = Auth::user(); // Authenticated user

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.',
        ], 403);
    }

    $id = $user->id;
//    echo $id;die;
    $perPage = $request->per_page ?? 6;
    $page = $request->input('page', 1);

    // Determine relationship & filter based on user type
    if ($user->user_type == 2) { // Coach
        $relation = 'coach';
        $filterColumn = 'user_id';
    } else { // Normal User
        $relation = 'user';
        $filterColumn = 'coach_id';
    }

$coachingRequests = CoachingRequest::with([
                        $relation . '.country',  
                        $relation . '.userProfessional.coachType', 
                        $relation . '.reviews', 
                    ])->where($filterColumn, $id)
                    ->orderBy('coaching_request.id', 'desc')
                    ->paginate($perPage, ['*'], 'page', $page);

        // print_r($coachingRequests);die;
        
$results = $coachingRequests->getCollection()->map(function ($req) use ($relation) {
    $show_relation = $relation;     
    $reviews = $req->$show_relation->reviews ?? collect();
    $avgRating = $reviews->avg('rating'); 
    return [
        'id'         => $req->$show_relation->id ?? null,
        'request_id' => $req->id ?? null,
        'coaching_request_goal' => $req->coaching_goal ?? null,
        'first_name' => $req->$show_relation->first_name ?? null,
        'last_name'  => $req->$show_relation->last_name ?? null,
        'user_type'  => $req->$show_relation->user_type ?? null,
        'coaching_category'    => $req->coach->userProfessional->coachType->type_name ?? null,
        'company_name'    => $req->$show_relation->company_name ?? null,
        'review_coach'    => $avgRating ?? null,
        'profile_image' => $req->$show_relation->profile_image
                    ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                    : '',
        'country'    => $req->$show_relation->country->country_name ?? null, // ✅ country name
    ];
});
//  echo 'test';die;
    return response()->json([
        'success' => true,
        'request_count' => $coachingRequests->total(),
        'data' => $results,
        'pagination' => [
            'total'        => $coachingRequests->total(),
            'per_page'     => $coachingRequests->perPage(),
            'current_page' => $coachingRequests->currentPage(),
            'last_page'    => $coachingRequests->lastPage(),
            'from'         => $coachingRequests->firstItem(),
            'to'           => $coachingRequests->lastItem(),
        ],
    ]);
}

public function getCoachingPackages(Request $request)
{
    $user = Auth::user(); // Authenticated user

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.',
        ], 403);
    }

    $id = $user->id;
    $perPage = $request->per_page ?? 6;
    $page = $request->input('page', 1);

    // echo $id;die;

    // Determine relationship & filter based on user type
    if ($user->user_type == 2) { // Coach
        $relation = 'coach';
        $filterColumn = 'user_id';
    } else { // Normal User
        $relation = 'user';
        $filterColumn = 'coach_id';
    }

    $bookPackages = BookingPackages::with([
        $relation . '.country',
        $relation . '.userProfessional.coachType',
    ])
        ->where($filterColumn, $id)
        ->orderBy('booking_packages.id', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

        // print_r($bookPackages);die;
    $results = $bookPackages->getCollection()->map(function ($req) use ($relation) {
        $show_relation = $relation;

        // echo $req->$show_relation->id;die;
        // echo $show_relation;die; 
       $now = Carbon::now()->startOfDay();

        // Full start datetime
        $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);

        // Full end datetime
        $endDateTime = Carbon::parse($req->session_date_end . ' ' . $req->slot_time_end);
        $endDate       = Carbon::parse($req->session_date_end)->endOfDay();
   
        $status = null;
        if($show_relation == 'coach'){
            if ($now->between($startDateTime, $endDateTime)) {
                $status = 'in-progress'; 
            } elseif ($now->lt($startDateTime)) {
                $status = 'confirmed'; 
            }else{
                return null;
            } 
        }  

         $sessionLeft = $now->lte($endDate) 
                ? $now->diffInDays($endDate) 
                : 0;
    //    print_r($sessionLeft);die;
        return [
            'id'                => $req->$show_relation->id ?? null,
            'booking_id'        => $req->id ?? null,
            'first_name'        => $req->$show_relation->first_name ?? null,
            'last_name'         => $req->$show_relation->last_name ?? null,
            'user_type'         => $req->$show_relation->user_type ?? null,
            'display_name'      => $req->$show_relation->display_name ?? null,
            'profile_image'     => $req->$show_relation->profile_image
                ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                : '',
            'session_date_start' => $req->session_date_start ?? null,
            'slot_time_start'    => $req->slot_time_start ?? null,
            'session_date_end'   => $req->session_date_end ?? null,
            'slot_time_end'      => $req->slot_time_end ?? null,
            'country'            => $req->$show_relation->country->country_name ?? null,
            'status'             => $status ?? null,
            'session_left' => $status ? ($status === 'active' ? 'session not started yet' : max(round($sessionLeft, 0) - 1, 0)) : null,
                            
        ];
    })->filter(); // remove nulls (completed ones)

    return response()->json([
        'success' => true,
        'request_count' => $results->count(),
        'data' => $results->values(),
        'pagination' => [
            'total'        => $bookPackages->total(),
            'per_page'     => $bookPackages->perPage(),
            'current_page' => $bookPackages->currentPage(),
            'last_page'    => $bookPackages->lastPage(),
            'from'         => $bookPackages->firstItem(),
            'to'           => $bookPackages->lastItem(),
        ],
    ]);
}

public function getPackagesCompleted(Request $request)
{
    $user = Auth::user(); // Authenticated user

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.',
        ], 403);
    }

    $id = $user->id;
    $perPage = $request->per_page ?? 6;
    $page = $request->input('page', 1);

    // echo $id;die;
    // Determine relationship & filter based on user type
    if ($user->user_type == 2) { // Coach
        $relation = 'coach';
        $filterColumn = 'user_id';
    } else { // Normal User
        $relation = 'user';
        $filterColumn = 'coach_id';
    }

    $bookPackages = BookingPackages::with([
        $relation . '.country',
        $relation . '.userProfessional.coachType',
    ])
        ->where($filterColumn, $id)
        ->orderBy('booking_packages.id', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

    $results = $bookPackages->getCollection()->map(function ($req) use ($relation) {
        $show_relation = $relation;
        $now = Carbon::now()->startOfDay();

        // Full start datetime
        $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);

        // Full end datetime
        $endDateTime = Carbon::parse($req->session_date_end . ' ' . $req->slot_time_end);
        $endDate     = Carbon::parse($req->session_date_end)->endOfDay();

        $status = null;

        // ✅ Completed Logic
        if ($now->gt($endDateTime)) {
            $status = 'completed';
        } else {
            return null; // only return completed ones
        }

        $sessionLeft = $now->lte($endDate) 
            ? $now->diffInDays($endDate) 
            : 0;

        return [
            'id'                => $req->$show_relation->id ?? null,
            'booking_id'        => $req->id ?? null,
            'first_name'        => $req->$show_relation->first_name ?? null,
            'last_name'         => $req->$show_relation->last_name ?? null,
            'user_type'         => $req->$show_relation->user_type ?? null,
            'display_name'      => $req->$show_relation->display_name ?? null,
            'profile_image'     => $req->$show_relation->profile_image
                ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                : '',
            'session_date_start' => $req->session_date_start ?? null,
            'slot_time_start'    => $req->slot_time_start ?? null,
            'session_date_end'   => $req->session_date_end ?? null,
            'slot_time_end'      => $req->slot_time_end ?? null,
            'country'            => $req->$show_relation->country->country_name ?? null,
            'status'             => $status ?? null,                 
        ];
    })->filter(); // only keep completed ones

    return response()->json([
        'success' => true,
        'request_count' => $results->count(),
        'data' => $results->values(),
        'pagination' => [
            'total'        => $bookPackages->total(),
            'per_page'     => $bookPackages->perPage(),
            'current_page' => $bookPackages->currentPage(),
            'last_page'    => $bookPackages->lastPage(),
            'from'         => $bookPackages->firstItem(),
            'to'           => $bookPackages->lastItem(),
        ],
    ]);
}



}