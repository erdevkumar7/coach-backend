<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\User;
use App\Models\CoachingRequest;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
    $perPage = $request->input('per_page', 10);
    $page = $request->input('page', 1);

    // Determine relationship & filter based on user type
    if ($user->user_type == 2) { // Coach
        $relation = 'user';
        $filterColumn = 'user_id';
    } else { // Normal User
        $relation = 'coach';
        $filterColumn = 'coach_id';
    }

    $coachingRequests = CoachingRequest::with([$relation])
        ->where($filterColumn, $id)
        ->orderBy('coaching_request.id', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

        // print_r($coachingRequests);die;
        
    $results = $coachingRequests->getCollection()->map(function ($req) use ($relation) {
          $show_relation = $relation;     
        return [
            'id'         => $req->$show_relation->id ?? null,
            'first_name' => $req->$show_relation->first_name ?? null,
            'last_name'  => $req->$show_relation->last_name ?? null,
            'user_type'  => $req->$show_relation->user_type ?? null,
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

    public function getPendingCoaching45(Request $request){

        $user = Auth::user(); // Authenticated user

        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        $id = $user->id;
        // echo $id;die;
           $perPage = $request->input('per_page', 10) ; 
           $page = $request->input('page', $request->page) ?? 1;
        // echo $id;die;
           $coachRequestShow =  CoachingRequest::with(['user'])
                                ->where('coach_id',$id)
                                ->orderBy('coaching_request.id','desc')
                                ->paginate($perPage, ['*'], 'page', $page);
                                // ->get();
            // print_r($coachRequestShow);die;

            $userRequestShow =  CoachingRequest::with(['coach'])
                        ->where('coach_id',$id)
                        ->orderBy('coaching_request.id','desc')
                        ->paginate($perPage, ['*'], 'page', $page);

                print_r($userRequestShow);die;
           $results = $coachRequestShow->getCollection()->map(function ($user) use ($request){ 
                
            if($user->user->user_type == 2){
            return  [
                'user_id'              => $user->user->id,
                'first_name'           => $user->user->first_name,
                'last_name'            => $user->user->last_name,
                'user_type'            => $user->user->user_type,

            ];
           }else{
                
            // echo "test";die;
             return  [
                'first_name'           => $user->user->first_name,
                'last_name'            => $user->user->last_name,
                'user_type'            => $user->user->user_type,

            ];

           }

         });

 

        return response()->json([
            'success' => true,
            'request_count'=> $coachRequestShow->total(),
            'data' => $results,
            'pagination' => [
                'total'        => $coachRequestShow->total(),
                'per_page'     => $coachRequestShow->perPage(),
                'current_page' => $coachRequestShow->currentPage(),
                'last_page'    => $coachRequestShow->lastPage(),
                'from'         => $coachRequestShow->firstItem(),
                'to'           => $coachRequestShow->lastItem(),
            ],
        ]);
        
    }
}