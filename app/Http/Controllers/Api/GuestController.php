<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingPackages;
use App\Models\HomeSetting;
use App\Models\MasterCity;
use App\Models\MasterState;
use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            ->select('id', 'type_name','image')
            ->where('is_active', 1)
            ->get()
            ->map(function ($coach_type) {
                $coach_type->image = $coach_type->image ? asset('public/uploads/blog_files/' . $coach_type->image): null;                                  
                return $coach_type;
             });
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

    public function getPrivacyPolicy()
    {
        // Fetch privacy policy data (policy_type = 1)
        $data = Policy::where('policy_type', 1)->where('is_deleted', 0)->get();

        if ($data->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Privacy Policy fetched successfully.',
                'data'    => $data,
            ], 200);
        }

        // If no data found
        return response()->json([
            'success' => false,
            'message' => 'No privacy policy found.',
            'data'    => [],
        ], 404);
    }

    public function termsAndConditions()
    {
        // Fetch privacy policy data (policy_type = 1)
        $data = Policy::where('policy_type', 2)->where('is_deleted', 0)->get();

        if ($data->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Terms & Conditions fetched successfully.',
                'data'    => $data,
            ], 200);
        }

        // If no data found
        return response()->json([
            'success' => false,
            'message' => 'No Terms & Conditions found.',
            'data'    => [],
        ], 404);
    }


    public function getHomePageSection()
    {
        $sections = HomeSetting::select('section_name', 'title', 'subtitle', 'description', 'image')
            ->get()
            ->map(function ($section) {
                $section->image = $section->image ? asset('public/uploads/blog_files/' . $section->image) : null;
                return $section;
            });

        if ($sections->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No home page sections found.',
                'data' => [],
            ], 404);
        }


        $available_coach_count = User::where('user_type', 3)
            ->where('is_deleted', 0)
            ->where('email_verified', 1)->count();

        $users = User::where('user_type', 2)
            ->where('is_deleted', 0)
            ->where('email_verified', 1)
            ->with(['userProfessional', 'languages']) // include languages relation
            ->get();

        $coaches = User::where('user_type', 3)
            ->where('is_deleted', 0)
            ->where('email_verified', 1)
            ->with(['userProfessional', 'languages'])
            ->get();

        $matches_made_count = 0;

        foreach ($users as $user) {
            foreach ($coaches as $coach) {

                // Extract language IDs for both
                $userLanguageIds = $user->languages->pluck('language_id')->toArray();
                $coachLanguageIds = $coach->languages->pluck('language_id')->toArray();

                // Check if there’s at least one language in common
                $languageMatch = count(array_intersect($userLanguageIds, $coachLanguageIds)) > 0;

                if (
                    $user->age_group == $coach->age_group &&
                    $user->country_id == $coach->country_id &&
                    $user->gender == $coach->gender &&
                    $languageMatch && // ✅ check for at least one common language
                    optional($user->userProfessional)->delivery_mode ==
                    optional($coach->userProfessional)->delivery_mode
                ) {
                    $matches_made_count++;
                }
            }
        }


        $coaching_goal_achieve_count  = BookingPackages::where('status', '!=', 3)
            ->whereRaw("CONCAT(session_date_end, ' ', slot_time_end) < ?", [Carbon::now()])
            ->count();

        $sections = $sections->toArray(); // convert collection to plain array


        $sections[] = [
            'section_name' => 'home_page_data',
            'available_coach_count' => $available_coach_count,
            'matched_count' => $matches_made_count,
            'coaching_goal_achieve_count' => $coaching_goal_achieve_count,
        ];

        return response()->json([
            'success' => true,
            'message' => 'All home page sections retrieved successfully.',
            'data' => $sections,
        ], 200);
    }
}
