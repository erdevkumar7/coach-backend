<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoachingRequest;
use App\Models\Professional;
use App\Models\User;
use App\Models\BookingPackages;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CochingRequestController extends Controller
{
    public function cochingRequestSend(Request $request)
    {
        // print_r($request->all());die;
        // echo "test";die;
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }
     
        $user_type = 3; // 3 user type is coach
        $coach_type = $request->coach_type; // category
        $coach_subtype = $request->coach_subtype; // sub category
        $delivery_mode = $request->preferred_mode_of_delivery; //
        $country = $request->location; // country
        $coach_gender = $request->coach_gender; // male female, othor
        $learner_age_group = $request->learner_age_group; // age group
        $preferred_coaching = $request->preferred_teaching_style; // Coaching category fld
        $only_certified_coach = $request->only_certified_coach; // verified coach
        $coach_experience_level = $request->coach_experience_level;
        $languageIds = $request->language_preference;            //[3, 4, 8];
        $budget_range = $request->budget_range;            
        $communication_channel = $request->preferred_communication_channel;            
        $preferred_start_date_urgency = $request->preferred_start_date_urgency;            
        $share_with_coaches = $request->share_with_coaches;            
        $preferred_schedule = $request->preferred_schedule;            

  
        $usersshow = User::with([
            'services',
            'languages',
            'userServicePackages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'coachsubtypeuser',
            'country',
        ])
            ->where('users.user_type', $user_type)

            // user type user or coach
            ->whereHas('userProfessional', function ($query) use ($coach_type) {
                $query->where('coach_type', $coach_type);
            })
            ->whereHas('coachsubtypeuser', function ($query) use ($coach_subtype) {
                    if (!empty($coach_subtype)) {
                        $query->where('coach_subtype_id', $coach_subtype);
                    }
            })
            ->whereHas('userProfessional', function ($query) use ($delivery_mode) {
                $query->where('delivery_mode', $delivery_mode);
            })
            ->where('users.country_id', $country)
            ->whereHas('userProfessional', function ($query) use ($learner_age_group) {
                $query->where('age_group', $learner_age_group);
            })
            ->whereHas('userProfessional', function ($query) use ($preferred_coaching) {
                $query->where('coaching_category', $preferred_coaching);
            })
    
            ->whereHas('userProfessional', function ($query) use ($coach_experience_level) {
                $query->where('experience', $coach_experience_level);
            })
            ->whereHas('languages', function ($query) use ($languageIds) {
                $query->whereIn('language_id', $languageIds);
            })
            ->where('users.gender', $coach_gender)
            ->when(!empty($coach_gender), function ($query) use ($coach_gender) {
                $query->where('users.gender', $coach_gender);
            })
            ->whereHas('userServicePackages', function ($query) use ($communication_channel) {
                $query->where('communication_channel', $communication_channel);
            })
             ->whereHas('userProfessional', function ($query) use ($budget_range) {
                $query->where('budget_range', $budget_range);
            })
             ->whereHas('userServicePackages', function ($query) use ($preferred_schedule) {
                $query->whereDate('booking_availability_end','>=', $preferred_schedule);
            })
        ->when(!empty($preferred_start_date_urgency), function ($query) use ($preferred_start_date_urgency) {
            $query->whereHas('userServicePackages', function ($q) use ($preferred_start_date_urgency) {
                $today = \Carbon\Carbon::today();

                if ($preferred_start_date_urgency == 1) {
                    // Immediate (within a week)
                    $q->whereDate('booking_availability_start', '<=', $today->copy()->addDays(7));
                } elseif ($preferred_start_date_urgency == 2) {
                    // Soon (1–2 weeks)
                    $q->whereBetween('booking_availability_start', [
                        $today->copy()->addDays(8),
                        $today->copy()->addDays(14)
                    ]);
                } 
                elseif ($preferred_start_date_urgency == 4 && !empty($specific_date)) {
                    // Specific Date — exact match
                    $q->whereDate('booking_availability_start', '=', \Carbon\Carbon::parse($specific_date));
                }
                // ID 3 (Flexible) — no filter applied
            });
        })

            ->where('users.is_verified', $only_certified_coach)
            ->where('users.is_deleted', 0)    
            ->orderBy('users.id', 'desc')
            ->get();


            // print_r($usersshow);die;

     //   return $usersshow->pluck('id');

        // return $usersshow->get();


        // Fetch matching coach IDs
        $coachIds = $usersshow->pluck('id');

        if ($coachIds->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No matching coaches found.',
            ]);
        }

        // Prepare common request data (excluding coach_id)
        $data = $request->only([
            'looking_for',
            'coaching_category',
            'coach_subtype',
            'coach_type',
            'preferred_mode_of_delivery',
            'location',
            'coaching_goal',
            'language_preference',
            'preferred_communication_channel',
            'learner_age_group',
            'preferred_teaching_style',
            'budget_range',
            'preferred_schedule',
            'coach_gender',
            'coach_experience_level',
            'only_certified_coach',
            'preferred_start_date_urgency',
            'special_requirements',
            'share_with_coaches',
        ]);

        $data['user_id'] = $user->id; // current user making the request
        $data['language_preference'] = json_encode($request->language_preference);
        $createdRequests = [];

        $data['coaching_category'] = $data['preferred_teaching_style'];
        unset($data['preferred_teaching_style']); 

        $data['looking_for'] = $data['coach_type'];
        unset($data['coach_type']); 
        
        if($share_with_coaches == 1){
        foreach ($coachIds as $coachId) {
            $data['coach_id'] = $coachId;

            $coachingRequest = CoachingRequest::create($data);
            $createdRequests[] = $coachingRequest;
        }

        return response()->json([
            'status' => true,
            'message' => 'Coaching request submitted successfully',
            'data' => $createdRequests
        ]);
       }else{
             return response()->json([
            'status' => true,
            'message' => 'Search the particular coach successfully',
            'data' => $usersshow
        ]);
       }
    }


     public function cochingRequestsListsUserDashboard(Request $request)
    {
        $user = Auth::user();
        // echo $user->id;die;
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $cochingRequestsList = CoachingRequest::with([
            'coach:id,first_name,last_name,display_name,profile_image,company_name'
        ])
        ->where('user_id', 73)
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

public function addPackageRequest(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        // Validate incoming request
        $validated = $request->validate([
            'package_id' => 'required|integer',
            'coach_id' => 'required|integer',
        ]);

        
        // Create a new BookingPackages entry
        $coachRequest = new BookingPackages();
        $coachRequest->package_id = $validated['package_id'];
        $coachRequest->coach_id = $validated['coach_id'];
        $coachRequest->user_id = $user->id; // authenticated user
        $coachRequest->slot_time_start = $request->slot_time_start;
        $coachRequest->slot_time_end = $request->slot_time_end;
        $coachRequest->session_date_start = $request->session_date_start;
        $coachRequest->session_date_end = $request->session_date_end;
        $coachRequest->amount = $request->amount;
        $coachRequest->delivery_mode = $request->delivery_mode;
        $coachRequest->save();

        // Prepare structured response data
        $data = [

            'package_id'        => $coachRequest->package_id,
            'coach_id'          => $coachRequest->coach_id,
            'slot_time_start'   => $coachRequest->slot_time_start,
            'slot_time_end'     => $coachRequest->slot_time_end,
            'session_date_start'=> $coachRequest->session_date_start,
            'session_date_end'  => $coachRequest->session_date_end,
            'amount'            => $coachRequest->amount,
            'delivery_mode'     => $coachRequest->delivery_mode,
            'delivery_mode_detail'     => $coachRequest->delivery_mode_detail,
      
            'user_details' => [
                    'id'              => $user->id,
                    'email'           => $user->email,
                    'first_name'      => $user->first_name,
                    'last_name'       => $user->last_name,
                    'user_type'       => $user->user_type,
                    'country_id'      => $user->country_id,
                    'profile_image'   => $user->profile_image
                                            ? url('public/uploads/profile_image/' . $user->profile_image)
                                : '',
            ]
        ];

        return response()->json([
            'status'  => true,
            'message' => 'Coach request submitted successfully.',
            'data'    => $data
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation error.',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong.',
            'error'   => $e->getMessage()
        ], 500);
    }
}





}