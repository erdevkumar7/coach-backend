<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoachingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CochingRequestController extends Controller
{
    public function cochingRequestAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'looking_for'                     => 'nullable|integer',
            'coaching_category'               => 'nullable|integer',
            'preferred_mode_of_delivery'      => 'nullable|integer',
            'location'                        => 'nullable|integer',
            'coaching_goal'                   => 'nullable|string',
            'language_preference'             => 'nullable|integer',
            'preferred_communication_channel' => 'nullable|integer',
            'learner_age_group'               => 'nullable|integer',
            'preferred_teaching_style'        => 'nullable|integer',
            'budget_range'                    => 'nullable|string|max:100',
            'preferred_schedule'              => 'nullable|string|max:100',
            'coach_gender'                    => 'nullable|integer', // tinyint(4)
            'coach_experience_level'          => 'nullable|integer',
            'only_certified_coach'            => 'nullable|boolean',
            'preferred_start_date_urgency'    => 'nullable|integer',
            'special_requirements'            => 'nullable|string',
            'is_active'                       => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Insert into DB
        $data = $request->only([
            'looking_for',
            'coaching_category',
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
            'is_active'
        ]);

        $coachingRequest = CoachingRequest::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Coaching request submitted successfully',
            'data' => $coachingRequest
        ]);
    }

}
