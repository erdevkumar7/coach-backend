<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterState;
use App\Models\MasterCity;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function getStatesOrCities(Request $request)
    {
        // echo "test";die;
        $countryId = $request->country_id;

        $states = MasterState::where('state_country_id', $countryId)->get();

        if ($states->count() > 0) {
            return response()->json([
                'type' => 'state',
                'data' => $states
            ]);
        }

        $cities = MasterCity::where('country_id', $countryId)
            ->whereNull('city_state_id')
            ->get();

        return response()->json([
            'type' => 'city',
            'data' => $cities
        ]);
    }
    public function getAllCountries()
    {

        $countries = DB::table('master_country')
            ->select('country_id', 'country_name')
            ->orderBy('country_name')
            ->get();
        return response()->json($countries);
    }

    public function getallmastercategories()
    {
        $countries = DB::table('master_country')
            ->select('country_id', 'country_name')
            ->orderBy('country_name')
            ->get();

        $delivery_mode = DB::table('delivery_mode')
            ->select('id', 'mode_name')
            ->where('is_active', 1)
            ->get();

        $languages = DB::table('master_language')
            ->select('id', 'language')
            ->where('is_active', 1)
            ->orderBy('language', 'asc')
            ->get();

        $age_group = DB::table('age_group')
            ->select('id', 'group_name', 'age_range')
            ->where('is_active', 1)
            ->get();

        $coaching_cat = DB::table('coaching_cat')
            ->select('id', 'category_name')
            ->where('is_active', 1)
            ->orderBy('category_name', 'asc')
            ->get();

        $formates = DB::table('master_session_format')
            ->select('id', 'name')
            ->where('is_active', 1)
            ->get();

        $priceModels = DB::table('master_price_model')
            ->select('id', 'name')
            ->where('is_active', 1)
            ->get();

        $coach_type = DB::table('coach_type')
            ->select('id', 'type_name')
            ->where('is_active', 1)
            ->orderBy('type_name', 'asc')
            ->get();

        $services = DB::table('master_service')
            ->select('id', 'service')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('service', 'asc')
            ->get();

        $master_cancellation_policies = DB::table('master_cancellation_policy')
            ->select('id', 'name')
            ->where('is_active', 1)
            ->get();

        $communication_channel = DB::table('communication_channel')
            ->select('id', 'category_name')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();
        $budget_ranage = DB::table('master_budget_ranges')
            ->select('id', 'budget_range')
            ->where('status', 1)
            ->get();
        $experience_leverl = DB::table('coach_experience_levels')
            ->select('id', 'experience_level')
            ->where('status', 1)
            ->get();
        // print_r($countries);die;\\
        return response()->json(['countries' => $countries, 'delivery_mode' => $delivery_mode, 'languages' => $languages, 'age_group' => $age_group, 'coaching_cat' => $coaching_cat, 'formates' => $formates, 'priceModels' => $priceModels, 'coach_type' => $coach_type, 'services' => $services, 'communication_channel' => $communication_channel, 'budget_range_show' => $budget_ranage, 'experience_level_show' => $experience_leverl, 'cancellation_policies' => $master_cancellation_policies]);
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

    // public function getAllSubCoachType($coach_type_id)
    // {
    //     $sub_coach_type = DB::table('coach_subtype')
    //         ->select('id', 'coach_type_id', 'subtype_name')
    //         ->where('is_active', 1)
    //         ->where('coach_type_id', $coach_type_id)
    //         ->get();
    //     return response()->json($sub_coach_type);
    // }

    public function getAllSubCoachType($coach_type_id = null)
    {
        $query = DB::table('coach_subtype')
            ->select('id', 'coach_type_id', 'subtype_name')
            ->where('is_active', 1);

        if ($coach_type_id) {
            $query->where('coach_type_id', $coach_type_id);
        }

        $sub_coach_type = $query->get();

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


    public function getAllCoachingCategories()
    {
        $coaching_cat = DB::table('coaching_cat')
            ->select('id', 'category_name')
            ->where('is_active', 1)
            ->get();
        return response()->json($coaching_cat);
    }

    public function getAllSessionFormats()
    {
        $formates = DB::table('master_session_format')
            ->select('id', 'name')
            ->where('is_active', 1)
            ->get();
        return response()->json($formates);
    }

    public function getAllPriceModels()
    {
        try {
            $priceModels = DB::table('master_price_model')
                ->select('id', 'name')
                ->where('is_active', 1)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $priceModels,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch price models.',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }

    public function getAllCoachServices()
    {
        try {
            $services = DB::table('master_service')
                ->select('id', 'service')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch price models.',
                'error' => $e->getMessage(), // optional: remove in production
            ], 500);
        }
    }
}
