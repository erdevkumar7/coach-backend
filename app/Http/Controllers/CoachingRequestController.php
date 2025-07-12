<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CoachingRequest;

class CoachingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $coach_req = DB::table('coaching_request as cr')
        //     ->join('coach_type', 'cr.looking_for', '=', 'coach_type.id')
        //     ->join('delivery_mode as dm', 'cr.preferred_mode_of_delivery', '=', 'dm.id')
        //     ->join('master_country as mc', 'cr.location', '=', 'mc.country_id')
        //     ->join('master_language as ml', 'cr.language_preference', '=', 'ml.id')
        //     ->select('cr.*', 'coach_type.type_name', 'dm.mode_name', 'mc.country_name', 'ml.language')
        //     ->get();

            // Fetching only requests created in the last 7 days
            $sevenDaysAgo = Carbon::now()->subDays(7);
            $coach_req = DB::table('coaching_request as cr')
                ->join('coach_type', 'cr.looking_for', '=', 'coach_type.id')
                ->join('delivery_mode as dm', 'cr.preferred_mode_of_delivery', '=', 'dm.id')
                ->join('master_country as mc', 'cr.location', '=', 'mc.country_id')
                ->join('master_language as ml', 'cr.language_preference', '=', 'ml.id')
                ->select('cr.*', 'coach_type.type_name', 'dm.mode_name', 'mc.country_name', 'ml.language')
                ->where('cr.created_at', '>=', $sevenDaysAgo)
                ->orderBy('cr.created_at', 'desc')
                ->get();


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
        //
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
