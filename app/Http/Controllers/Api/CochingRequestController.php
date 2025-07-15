<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoachingRequest;
use App\Models\Professional;
use App\Models\User;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CochingRequestController extends Controller
{
    public function cochingRequestSend(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }



        $validator = Validator::make($request->all(), [
            'looking_for'                     => 'nullable|integer',
            'coaching_category'               => 'required|integer',
            'preferred_mode_of_delivery'      => 'required|integer',
            'location'                        => 'required|integer',
            'coaching_goal'                   => 'required|string',
            'language_preference'             => 'required|integer',
            'preferred_communication_channel' => 'required|integer',
            'learner_age_group'               => 'required|integer',
            'preferred_teaching_style'        => 'required|integer',
            'budget_range'                    => 'required|string|max:100',
            'preferred_schedule'              => 'required|string|max:100',
            'coach_gender'                    => 'required|integer', // tinyint(4)
            'coach_experience_level'          => 'required|integer',
            'only_certified_coach'            => 'required|boolean',
            'preferred_start_date_urgency'    => 'required|integer',
            'special_requirements'            => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $delivery_mode = $request->preferred_mode_of_delivery;

    //     $users = Professional::whereHas('deliveryMode', function ($query) {
    //         $query->where('id', 2);
    //     })->get();


    // return $users;



        $usersshow = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city',
            'reviews'
        ])
            ->where('users.user_type', 3)
            ->whereHas('userProfessional', function ($query) {
                $query->where('delivery_mode', 2);
            })
            ->orderBy('users.id', 'desc');




        return $usersshow->get();




        // Insert into DB
        // $data = $request->only([
        //     'looking_for',
        //     'coach_id',
        //     'coaching_category',
        //     'preferred_mode_of_delivery',
        //     'location',
        //     'coaching_goal',
        //     'language_preference',
        //     'preferred_communication_channel',
        //     'learner_age_group',
        //     'preferred_teaching_style',
        //     'budget_range',
        //     'preferred_schedule',
        //     'coach_gender',
        //     'coach_experience_level',
        //     'only_certified_coach',
        //     'preferred_start_date_urgency',
        //     'special_requirements',
        //     'is_active'
        // ]);
        // $data['user_id'] = $user->id;
        // $coachingRequest = CoachingRequest::create($data);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Coaching request submitted successfully',
        //     'data' => $coachingRequest
        // ]);
    }


    public function cochingRequestsListsUserDashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $cochingRequestsList = CoachingRequest::with([
            'coach:id,first_name,last_name,display_name,profile_image,company_name'
        ])
        ->where('user_id', $user->id)
        ->where('is_active', 1)
        ->get()
        ->map(function ($request) {
            $avgRating = optional($request->coach->reviews()->where('is_deleted', 0))->avg('rating');
            $request->average_rating = round($avgRating, 1);
            return $request;
        });


        //$cochingRequestsList['reviews'] =  $user->reviews->avg('rating');
        return response()->json([
            'status' => true,
            'message' => 'Coaching request list',
            'data' => $cochingRequestsList
        ]);
    }

}
