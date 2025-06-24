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
        $cities=DB::table('master_city')
        ->where('city_state_id',$state_id)
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
}
