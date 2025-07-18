<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CoachingRequest;
use App\Models\User;

class CoachingRequestController extends Controller
{
    private function getUniqueCoachingRequests($requestId = null)
    {
        $query = DB::table('coaching_request as cr')
                ->join('users as user', 'cr.user_id', '=', 'user.id')
                ->join('coach_type as ct', 'cr.looking_for', '=', 'ct.id')
                ->join('delivery_mode as dm', 'cr.preferred_mode_of_delivery', '=', 'dm.id')
                ->join('master_country as mc', 'cr.location', '=', 'mc.country_id')
                ->join('master_language as ml', 'cr.language_preference', '=', 'ml.id')
                ->select(
                    DB::raw('MIN(cr.id) as id'),
                    'user.first_name',
                    'cr.request_id',
                    'cr.user_id',
                    'cr.coaching_goal',
                    'cr.budget_range',
                    'cr.preferred_schedule',
                    'cr.coach_gender',
                    'cr.coach_experience_level',
                    'ct.type_name',
                    'dm.mode_name',
                    'mc.country_name',
                    'ml.language'
                )
                ->groupBy(
                    'cr.request_id',
                    'cr.user_id',
                    'cr.looking_for',
                    'cr.coaching_category',
                    'cr.preferred_mode_of_delivery',
                    'cr.location',
                    'cr.coaching_goal',
                    'cr.language_preference',
                    'cr.preferred_communication_channel',
                    'cr.learner_age_group',
                    'cr.preferred_teaching_style',
                    'cr.budget_range',
                    'cr.preferred_schedule',
                    'cr.coach_gender',
                    'cr.coach_experience_level',
                    'cr.only_certified_coach',
                    'cr.preferred_start_date_urgency',
                    'ct.type_name',
                    'dm.mode_name',
                    'mc.country_name',
                    'ml.language',
                    'user.first_name'
                );
                 if($requestId) {
                     $query->where('cr.request_id', $requestId);
                    }

                return $query->get();

    }
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coach_req=$this->getUniqueCoachingRequests();
        return view('admin.coaching_requests', compact('coach_req'));
    }

    public function generateCoachingRequestForCoach()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $coach_req = $this->getUniqueCoachingRequests($id)->first();
         //dd($coach_req );

        $coachRequests = CoachingRequest::with('coach')
            ->where('request_id', $id)
            ->get();

        // Use pluck to get all coach models, then unique, then extract fields
        $eligible_coaches = $coachRequests
            ->pluck('coach')
             ->filter() // Ensure we only include coaches that are not null
            ->unique('id')
            ->map(function ($coach) {
                return [
                    'id' => $coach->id,
                    'display_name' => $coach->first_name . ' ' . $coach->last_name,
                    'email' => $coach->email,
                    'contact_number' => $coach->contact_number,
                ];
            })
            ->values(); // reset index
     //dd($eligible_coaches);
        return view('admin.view_coaching_request', compact('eligible_coaches','coach_req'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
