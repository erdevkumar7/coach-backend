<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function getAllCountries()
    {

        $countries = DB::table('master_country')
            ->select('country_id', 'country_name')
            ->orderBy('country_name')
            ->get();
        return response()->json($countries);
    }

    public function getStateOfaCountry($country_id)
    {
        $states = DB::table('master_state')
            ->where('state_country_id', $country_id)
            ->get();
        return response()->json($states);
    }

    public function getCitiesOfaState($state_id)
    {
        $cities = DB::table('master_city')
            ->where('city_state_id', $state_id)
            ->get();
        return response()->json($cities);
    }

    public function deliveryAllMode()
    {
        $mode = DB::table('delivery_mode')
            ->select('id', 'mode_name')
            ->where('is_active', 1)
            ->get();
        return response()->json($mode);
    }

    public function getAllLanguages()
    {
        $languages = DB::table('master_language')
            ->select('id', 'language')
            ->where('is_active', 1)
            ->get();
        return response()->json($languages);
    }

    public function getAllCoachType()
    {
        $coach_type = DB::table('coach_type')
            ->select('id', 'type_name')
            ->where('is_active', 1)
            ->get();
        return response()->json($coach_type);
    }

    public function getAllSubCoachType($coach_type_id)
    {
        $sub_coach_type = DB::table('coach_subtype')
            ->select('id', 'coach_type_id', 'subtype_name')
            ->where('is_active', 1)
            ->where('coach_type_id', $coach_type_id)
            ->get();
        return response()->json($sub_coach_type);
    }

    public function getAllAgeGroup()
    {
        $age_group = DB::table('age_group')
            ->select('id', 'group_name', 'age_range')
            ->where('is_active', 1)
            ->get();
        return response()->json($age_group);
    }
}
