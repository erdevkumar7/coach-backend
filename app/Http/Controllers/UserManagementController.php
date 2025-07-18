<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Professional;
use App\Models\UserService;
use App\Models\UserLanguage;
use App\Models\MasterEnquiry;
use App\Models\CoachSubType;
 use App\Models\UserNotificationSetting;

use App\Models\UserPrivacySetting;


class UserManagementController extends Controller
{
    public function __construct()
    {
        if (Auth::guard("admin")->user()) {
            $user = Auth::guard("admin")->user();

            if ($user->user_type != 1) {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }
        }
    }

    public function userList()
    {
        $users = DB::table('users')
            ->join('master_country', 'master_country.country_id', '=', 'users.country_id')
            ->where('user_type', 2)
            ->where('is_deleted', 0)
            ->select('users.*', 'master_country.country_name')
            ->orderBy('id', 'DESC')
            ->paginate(20);
        return view('admin.user_list', compact('users'));
    }
    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->user);
        $user->user_status = $request->status;
        $user->save();
    }
    public function deleteUser(Request $request)
    {
        //This function is for ajax to delete the user
        $user = User::find($request->user);
        $user->is_deleted = 1;
        $user->save();
    }
    public function addUser(Request $request, $id = null)
    {
        $country = DB::table('master_country')->where('country_status', 1)->get();
        $mode = DB::table('delivery_mode')->where('is_active', 1)->get();
        $language = DB::table('master_language')->where('is_active', 1)->get();
        $ageGroup = DB::table('age_group')->where('is_active', 1)->get();
        $coachingTiming = DB::table('coaching_timings')->where('is_active', 1)->get();


        $user_detail = $state = $city = "";
        if ($id != null) {
            //$user_detail = DB::table('users')->where('id', $id)->first();
            $user_detail =User::with(['notificationSettings', 'privacySettings'])->find($id);
            $state = DB::table('master_state')->where('state_country_id', $user_detail->country_id)->get();
            // dd($state);
            $city = DB::table('master_city')->where('city_state_id', $user_detail->state_id)->get();
        }
        if ($request->isMethod('post')) {

            $user = User::find($request->user_id);

            if (!$user) {
                $user = new User();
            }

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/profile_image'), $imageName);
                $user->profile_image = $imageName;
            }

            $user->first_name       = $request->first_name;
            $user->last_name        = $request->last_name;
            $user->display_name  = $request->display_name;
            $user->professional_profile = $request->professional_profile;
            $user->email            = $request->email;
            $user->contact_number   = $request->contact_number;
            $user->age_group   = $request->age_group;
            $user->coaching_topics = $request->coaching_topics;
            $user->user_profession =$request->your_profession;
            $user->short_bio = $request->short_bio;
            $user->coaching_goal_1 = $request->coaching_goal1;
            $user->coaching_goal_2 = $request->coaching_goal2;
            $user->coaching_goal_3 = $request->coaching_goal3;
            $user->coaching_time = $request->coaching_time;
            $user->delivery_mode = $request->delivery_mode;
            $user->pref_lang = $request->prefered_lang;

            if ($request->password != '') {
                $user->password         = $request->password;
            }

            $user->gender           = $request->gender;
            $user->country_id       = $request->country_id;
            $user->state_id         = $request->state_id;
            $user->city_id          = $request->city_id;
            $user->user_type        = 2;
            $user->user_timezone    = $request->user_time;
            $user->email_verified   = 1;
            $user->created_at       = date('Y-m-d H:i:s');
            $user->save();


            return redirect()->route("admin.userList")->with("success", "User profile updated successfully.");
        }

        return view('admin.add_user', compact('country', 'user_detail', 'mode', 'state', 'city','language','ageGroup','coachingTiming',));
    }

    public function updateUserCoachPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message'=>'Password updated successfully.'
        ]);
    }

    public function updateNotificationSetting(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'field' => 'required|string',
            'value' => 'required|boolean',
        ]);

        $user = User::find($request->user_id);

        // get or create notification setting
        $setting = $user->notificationSettings ?? new UserNotificationSetting(['user_id' => $user->id]);
        // update the specific field dynamically
        if (in_array($request->field, [
            'new_coach_match_alert', 'message_notifications', 'booking_reminders',
            'platform_announcements', 'blog_recommendations', 'billing_updates'
        ])) {
            $setting->{$request->field} = $request->value;
            $setting->save();

            return response()->json([
                'success' => true,
                'message'=>'Notification has Updated'
            ]);
        }

        return response()->json(['error' => 'Invalid field'], 400);
    }

    public function updateProfileVisibility(Request $request)
    {
            // Step 1: Validate input
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'profile_visibility' => 'required|in:public,private',
            ]);

            // Step 2: Find the user
            $user = User::find($request->user_id);

            // Step 3: Either get existing or create new privacy setting record
            $privacy = $user->privacySettings ?? new UserPrivacySetting();

            // Step 4: Fill and save
            $privacy->user_id = $user->id;
            $privacy->profile_visibility = $request->profile_visibility;
            $privacy->save();

            // Step 5: Return response
            return response()->json([
                'success' => true,
                'message' => 'Profile visibility updated successfully.',
                'data' => $privacy,
            ]);
    }

    public function updateCommunicationPreference(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:communication_email,communication_in_app,communication_push',
            'value' => 'required|in:0,1',
        ]);

        $user = User::find($request->user_id);

        $setting = $user->privacySettings ?? new UserPrivacySetting();
        $setting->user_id = $user->id;

        // Only update the requested field
        $field = $request->type;
        $setting->$field = $request->value;

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => ucwords(str_replace('_', ' ', $field)) . ' updated successfully.',
            'updated_value' => $setting->$field
        ]);
    }

    public function updateAiPersonalization(Request $request)
    {
         $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:ai_personalization_agreed',
            'value' => 'required|in:0,1',
        ]);

        $user = User::find($request->user_id);

        $setting = $user->privacySettings ?? new UserPrivacySetting();
        $setting->user_id = $user->id;

        // Only update the requested field
        $field = $request->type;
        $setting->$field = $request->value;

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => ucwords(str_replace('_', ' ', $field)) . ' updated successfully.',
            'updated_value' => $setting->$field
        ]);
    }

    public function updateCookiePreference(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'accept_all' =>'sometimes|in:true,false,1,0', // Only validate 'accept_all' if it exists
            // Only validate type/value if accept_all is NOT sent
            'type' => 'required_without:accept_all|string',
            'value' => 'required_without:accept_all|in:0,1',
        ]);


        // Allowed fields only
        $allowedFields = [
            'essential_cookies',
            'performance_cookies',
            'functional_cookies',
            'marketing_cookies',
        ];

        $user = User::find($request->user_id);
        $setting = $user->privacySettings ?? new UserPrivacySetting(['user_id' => $user->id]);

        if ($request->has('accept_all')) {

            $accept = filter_var($request->accept_all, FILTER_VALIDATE_BOOLEAN);

            foreach ($allowedFields as $field) {
                $setting->$field = $accept ? 1 : 0;
            }

            $setting->accepted_all_cookies = $accept ? 1 : 0;
            $setting->rejected_all_cookies = $accept ? 0 : 1;
            $setting->save();

            return response()->json([
                'success' => true,
                'message' => $accept
                    ? 'All cookies accepted.'
                    : 'All cookies disabled.',
            ]);
        }

        //Handle Single Field Update
        $field = $request->type;
        if (!in_array($field, $allowedFields)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid field.',
            ], 400);
        }

        $setting->$field = $request->value;
         $setting->accepted_all_cookies =  0;
        $setting->save();

        return response()->json([
            'success'       => true,
            'message'       => "$field updated.",
            'updated_value' => $setting->$field,
        ]);
    }



    public function viewUser($id)
    {
        if ($id != null) {
            $user_detail = DB::table('users')
                ->join('master_country as mc', 'users.country_id', '=', 'mc.country_id')
                ->join('master_state as ms', 'users.state_id', '=', 'ms.state_id')
                ->join('master_city as c', 'users.city_id', '=', 'c.city_id')
                ->join('age_group as ag', 'users.age_group', '=', 'ag.id')
                ->join('coaching_timings as ct', 'users.coaching_time', '=', 'ct.id')
                ->join('master_language as ml', 'users.pref_lang', '=', 'ml.id')
                ->join('delivery_mode as dm', 'users.delivery_mode', '=', 'dm.id')
                ->select('users.*', 'mc.country_name', 'ms.state_name', 'c.city_name','ag.group_name','ag.age_range','ct.timing_label','ml.language','dm.mode_name')
                ->where('users.id', $id)->first();

                //  dd($user_detail);

            $enquiry = DB::table('enquiry')
                ->join('users as user', 'user.id', '=', 'enquiry.user_id')
                ->select(
                    'user.id as user_id',
                    'user.first_name as user_first_name',
                    'user.last_name as user_last_name',
                    'user.email as user_email',
                    'user.contact_number as user_contact_number',
                    'enquiry.enquiry_status as user_enquiry_status',
                    'enquiry.id',
                    'enquiry.enquiry_title',
                    'enquiry.enquiry_detail'
                )->where('enquiry.user_id', $id)
                ->orderBy('enquiry.id', 'DESC')
                ->paginate(20);
        }
        return view('admin.view_user_profile', compact('user_detail', 'enquiry'));
    }
    public function coachList()
    {
        //This function is for list the coach
        $users = DB::table('users')
            ->join('master_country', 'master_country.country_id', '=', 'users.country_id')
            ->where('user_type', 3)
            ->where('is_deleted', 0)
            ->select('users.*', 'master_country.country_name')
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('admin.coach_list', compact('users'));
    }
    public function addCoach(Request $request, $id = null)
    {

        $country = DB::table('master_country')->where('country_status', 1)->get();
        $language = DB::table('master_language')->where('is_active', 1)->get();
        $service = DB::table('master_service')->where('is_active', 1)->get();
        $type = DB::table('coach_type')->where('is_active', 1)->get();
        $category = DB::table('coaching_cat')->where('is_active', 1)->get();
        $mode = DB::table('delivery_mode')->where('is_active', 1)->get();

        $subtype = $user_detail = $state = $city = $profession = "";
        $selectedServiceIds = $selectedLanguageIds = array();
        if ($id != null) {
            $user_detail =User::with(['notificationSettings', 'privacySettings'])->find($id);
            dd($user_detail);
            // $user_detail = DB::table('users')->where('id', $id)->first();
             dd($user_detail);
            $state = DB::table('master_state')->where('state_country_id', $user_detail->country_id)->get();
            $city = DB::table('master_city')->where('city_state_id', $user_detail->state_id)->get();

            $profession = DB::table('user_professional')->where('user_id', $id)->first();

            $subtype = DB::table('coach_subtype')->where('coach_type_id', $profession->coach_type)->get();

            $selectedServiceIds = UserService::where('user_id', $id)->pluck('service_id')->toArray();
            $selectedLanguageIds = UserLanguage::where('user_id', $id)->pluck('language_id')->toArray();

        }
        if ($request->isMethod('post')) {
            // return dd($request);
            $user = User::find($request->user_id);
            if (!$user) {
                $user = new User();
            }

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/profile_image'), $imageName);
                $user->profile_image = $imageName;
            }

            $user->first_name       = $request->first_name;
            $user->last_name        = $request->last_name;
            $user->email            = $request->email;
            $user->contact_number   = $request->contact_number;
            $user->short_bio        = $request->short_bio;
            if ($request->password != '') {
                $user->password         = $request->password;
            }
            $user->professional_title = $request->professional_title;
            $user->gender           = $request->gender;
            $user->country_id       = $request->country_id;
            $user->state_id         = (int) $request->state_id;
            $user->city_id          = (int) $request->city_id;
            $user->is_verified      = $request->is_verified;
            $user->user_type        = 3;
            $user->user_timezone    = $request->user_time;
            $user->email_verified   = 1;
            $user->created_at       = date('Y-m-d H:i:s');
            $user->save();
            // Assuming $request->coach_subtype contains: [23, 24, 25]
            $coachSubtypeIds = $request->input('coach_subtype', []);
            $user->coachSubtypes()->sync($coachSubtypeIds);
            $user_id = $user->id;

            //Now update the professional profile

            $professional = Professional::where('user_id', $user_id)->first();

            if (!$professional) {
                $professional = new Professional();
                $professional->user_id = $user_id;
            }

            $professional->coaching_category    = $request->coaching_category;
            $professional->delivery_mode        = $request->delivery_mode;
            $professional->free_trial_session   = $request->free_trial_session;
            $professional->is_volunteered_coach = $request->is_volunteered_coach;
            $professional->volunteer_coaching   = $request->volunteer_coaching;
            $professional->coach_type           = (int) $request->coach_type;
            // $professional->coach_subtype        = $request->coach_subtype;
            $professional->save();


            //now add the service
            if ($request->service_offered) {
                $newServiceIds = $request->input('service_offered', []);

                $existingServiceIds = UserService::where('user_id', $user_id)
                    ->pluck('service_id')
                    ->toArray();

                // Find services to remove
                $toDelete = array_diff($existingServiceIds, $newServiceIds);

                // Find services to add
                $toAdd = array_diff($newServiceIds, $existingServiceIds);

                // Delete unselected services
                UserService::where('user_id', $user_id)
                    ->whereIn('service_id', $toDelete)
                    ->delete();

                // Add new services
                foreach ($toAdd as $serviceId) {
                    UserService::create([
                        'user_id' => $user_id,
                        'service_id' => $serviceId,
                    ]);
                }
            }

            if ($request->language) {
                $newlangIds = $request->input('language', []);

                $existingLanguageIds = UserLanguage::where('user_id', $user_id)
                    ->pluck('language_id')
                    ->toArray();

                // Find services to remove
                $toDeletel = array_diff($existingLanguageIds, $newlangIds);

                // Find services to add
                $toAddl = array_diff($newlangIds, $existingLanguageIds);

                // Delete unselected services
                UserLanguage::where('user_id', $user_id)
                    ->whereIn('language_id', $toDeletel)
                    ->delete();

                // Add new services
                foreach ($toAddl as $languageId) {
                    UserLanguage::create([
                        'user_id' => $user_id,
                        'language_id' => $languageId,
                    ]);
                }
            }

            return redirect()->route("admin.coachList")->with("success", "Coach profile updated successfully.");
        }

        return view('admin.add_coach', compact('category', 'mode', 'type', 'subtype', 'country', 'user_detail', 'state', 'city', 'profession', 'language', 'service', 'selectedServiceIds', 'selectedLanguageIds'));
    }
    public function addProfessional(Request $request, $id = null)
    {
        //This function is for add coach professional
        $user_detail = $profession = $document = "";
        if ($id != null) {
            $user_detail = DB::table('users')->where('id', $id)->first();
            $profession = DB::table('user_professional')->where('user_id', $id)->first();
            $document = DB::table('user_document')->where('user_id', $id)->get();
        }

        if ($request->isMethod('post')) {
            $user_id = $request->user_id;
            $professional = Professional::where('user_id', $user_id)->first();

            if (!$professional) {
                $professional = new Professional();
                $professional->user_id = $user_id;
            }

            $professional->experience    = $request->experiance;
            $professional->price        = $request->price;
            $professional->video_link   = $request->video_introduction;
            $professional->website_link = $request->website;
            $professional->fb_link      = $request->facebook;
            $professional->insta_link = $request->instagram;
            $professional->linkdin_link = $request->linkdin;
            $professional->booking_link = $request->booking;
            $professional->objective   = $request->objective;
            $professional->save();

            $user = User::find($request->user_id);
            $user->detailed_bio = $request->detailed_bio;
            $user->exp_and_achievement = $request->exp_and_achievement;
            $user->save();

            //Now add the files
            if ($request->hasFile('document_file')) {
                $documents = $request->file('document_file');
                $types = $request->input('document_type');
                $docIds = $request->input('doc_id', []); // Optional

                foreach ($documents as $index => $file) {
                    if ($file && $file->isValid()) {
                        $filename = $file->getClientOriginalName();
                        $imageName = time() . rand() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('/uploads/documents'), $imageName);

                        $documentData = [
                            'document_file' => $imageName,
                            'original_name' => $filename,
                            'document_type' => $types[$index] ?? null,
                            'updated_at' => now()
                        ];

                        // Check if we're updating or inserting
                        if (!empty($docIds[$index])) {
                            // Update existing document
                            DB::table('user_document')
                                ->where('id', $docIds[$index])
                                ->update($documentData);
                        } else {
                            // Insert new document
                            $documentData['user_id'] = $request->user_id;
                            $documentData['created_at'] = now();

                            DB::table('user_document')->insert($documentData);
                        }
                    }
                }
            }

            return redirect()->route("admin.coachList")->with("success", "Professional profile updated successfully.");
        }
        return view('admin.add_professional', compact('user_detail', 'profession', 'document'));
    }
    public function deleteDocument(Request $request)
    {
        $doc = DB::table('user_document')->where('id', $request->id)->first();

        if ($doc) {
            // Delete the file from filesystem
            $path = public_path('/uploads/documents/' . $doc->document_file);
            if (file_exists($path)) {
                unlink($path);
            }

            // Delete from DB
            DB::table('user_document')->where('id', $request->id)->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
    public function coachProfile(Request $request, $id = null)
    {
        $country = DB::table('master_country')->where('country_status', 1)->get();
        $language = DB::table('master_language')->where('is_active', 1)->get();
        $service = DB::table('master_service')->where('is_active', 1)->get();
        $type = DB::table('coach_type')->where('is_active', 1)->get();
        $category = DB::table('coaching_cat')->where('is_active', 1)->get();
        $mode = DB::table('delivery_mode')->where('is_active', 1)->get();

        $subtype = $user_detail = $state = $city = $profession = $document = "";
        $selectedServiceIds = $selectedLanguageIds = array();
        if ($id != null) {
            // $user_detail = DB::table('users')->where('id', $id)->first();
            $user_detail=User::with(['notificationSettings', 'privacySettings'])->find($id);
            // return dd($user_detail);
            $state = DB::table('master_state')->where('state_country_id', $user_detail->country_id)->get();
            $city = DB::table('master_city')->where('city_state_id', $user_detail->state_id)->get();

            // $profession = DB::table('user_professional')->where('user_id', $id)->first();
            $profession=Professional::where('user_id', $id)->first();
            // return dd($profession);

            $subtype = collect(); // Default to empty if no profession

            if ($profession && isset($profession->coach_type)) {
                $subtype = DB::table('coach_subtype')
                    ->where('coach_type_id', $profession->coach_type)
                    ->get();
            }

            $user = User::with('coachSubtypes')->find($id);
            $coach_subtype_ids = $user->coachSubtypes->pluck('id')->toArray();

            if ($profession && !empty($coach_subtype_ids)) {
                $profession->coach_subtype_data = $coach_subtype_ids;

            }

            $selectedServiceIds = UserService::where('user_id', $id)->pluck('service_id')->toArray();
            $selectedLanguageIds = UserLanguage::where('user_id', $id)->pluck('language_id')->toArray();

            $document = DB::table('user_document')->where('user_id', $id)->get();

        }

        return view('admin.coach_profile', compact('document', 'category', 'mode', 'type', 'subtype', 'country', 'user_detail', 'state', 'city', 'profession', 'language', 'service', 'selectedServiceIds', 'selectedLanguageIds'));
    }
    public function viewCoach($id)
    {
        //This function is for view the coach profile
        if ($id != null) {
            $user_detail = DB::table('users')
                ->join('master_country as mc', 'users.country_id', '=', 'mc.country_id')
                ->join('master_state as ms', 'users.state_id', '=', 'ms.state_id')
                ->join('master_city as c', 'users.city_id', '=', 'c.city_id')
                ->select('users.*', 'mc.country_name', 'ms.state_name', 'c.city_name')
                ->where('id', $id)->first();

            $profession = DB::table('user_professional as up')
                ->join('coach_type as ct', 'up.coach_type', '=', 'ct.id')
                ->join('coaching_cat as cat', 'up.coaching_category', '=', 'cat.id')
                ->join('delivery_mode as dm', 'up.delivery_mode', '=', 'dm.id')
                ->select('up.*', 'ct.type_name','cat.category_name', 'dm.mode_name')
                ->where('up.user_id', $id)
                ->first();
            // If professional data exists, get the subtypes
            if ($profession) {
                    $subtypes = DB::table('coach_subtype_user as csu')
                        ->join('coach_subtype as cst', 'csu.coach_subtype_id', '=', 'cst.id')
                        ->where('csu.user_id', $id)
                        ->pluck('cst.subtype_name');
                    // Attach the subtypes to the profession object
                    $profession->subtype_name = $subtypes->implode(', ');
            }

            $language = DB::table('user_language as ul')
                ->join('master_language as ml', 'ul.language_id', '=', 'ml.id')
                ->where('ul.user_id', $id)
                ->select(DB::raw('GROUP_CONCAT(ml.language SEPARATOR ", ") as language_names'))
                ->first();

            $service = DB::table('user_service as us')
                ->join('master_service as ms', 'us.service_id', '=', 'ms.id')
                ->where('us.user_id', $id)
                ->select(DB::raw('GROUP_CONCAT(ms.service SEPARATOR ", ") as service_names'))
                ->first();

            $coach_enquiry = DB::table('enquiry')
                ->join('users as coach', 'coach.id', '=', 'enquiry.coach_id')
                ->select(
                    'coach.id as coach_id',
                    'coach.first_name as coach_first_name',
                    'coach.last_name as coach_last_name',
                    'coach.email as coach_email',
                    'coach.contact_number as coach_contact_number',
                    'enquiry.enquiry_status as coach_enquiry_status',
                    'enquiry.id',
                    'enquiry.enquiry_title',
                    'enquiry.enquiry_detail'
                )
                ->where('enquiry.coach_id', $id)
                ->orderBy('enquiry.id', 'DESC')
                ->paginate(20);


            $document = DB::table('user_document')->where('user_id', $id)->get();
        }
        return view('admin.view_coach_profile', compact('document', 'user_detail', 'profession', 'language', 'service', 'coach_enquiry'));
    }

    public function enquiry_status(Request $request)
    {
        $user = MasterEnquiry::find($request->user);
        $user->enquiry_status = $request->status;
        $user->save();
    }
    public function bulkDeleteusr(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No user selected.');
        }

        User::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected user deleted successfully.');
    }
    public function bulkDeleteCoach(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Coach selected.');
        }

        User::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Coach deleted successfully.');
    }

    public function view_user_enquiry($id)
    {

        //dd($id);

        if ($id != null) {

            $user_detail = DB::table('enquiry')
                ->join('users as user', 'user.id', '=', 'enquiry.user_id')
                ->leftJoin('master_country', 'user.country_id', '=', 'master_country.country_id')
                ->leftJoin('master_state', 'user.state_id', '=', 'master_state.state_id')
                ->leftJoin('master_city', 'user.city_id', '=', 'master_city.city_id')
                ->select(
                    'user.id as user_id',
                    'user.first_name as user_first_name',
                    'user.last_name as user_last_name',
                    'user.email as user_email',
                    'user.contact_number as user_contact_number',
                    'user.professional_title as user_professional_title',
                    'user.short_bio as user_short_bio',
                    'user.gender as user_gender',
                    'user.detailed_bio as user_detailed_bio',
                    'user.profile_image as user_profile_image',
                    'master_country.country_name',
                    'master_state.state_name',
                    'master_city.city_name',
                    'enquiry.id',
                    'enquiry.enquiry_title',
                    'enquiry.enquiry_detail'
                )
                ->where('enquiry.id', $id)
                ->where('user.user_type', 2)
                ->where('user.user_status', 1)
                ->first();


            $enquiry_detail = DB::table('enquiry')
                ->join('users', 'users.id', '=', 'enquiry.user_id')
                ->select('users.*', 'enquiry.enquiry_title', 'enquiry.enquiry_detail')
                ->where('enquiry.id', $id)
                ->first();
        }

        return view('admin.view_user_enquiry', compact('user_detail', 'enquiry_detail', 'id'));
    }


    public function view_coach_enquiry($id)
    {

        //dd($id);

        if ($id != null) {

            $coach_detail = DB::table('enquiry')
                ->join('users as coach', 'coach.id', '=', 'enquiry.coach_id')
                ->leftJoin('master_country', 'coach.country_id', '=', 'master_country.country_id')
                ->leftJoin('master_state', 'coach.state_id', '=', 'master_state.state_id')
                ->leftJoin('master_city', 'coach.city_id', '=', 'master_city.city_id')
                ->select(
                    'coach.id as coach_id',
                    'coach.first_name as coach_first_name',
                    'coach.last_name as coach_last_name',
                    'coach.email as coach_email',
                    'coach.contact_number as coach_contact_number',
                    'coach.professional_title as coach_professional_title',
                    'coach.short_bio as coach_short_bio',
                    'coach.gender as coach_gender',
                    'coach.detailed_bio as coach_detailed_bio',
                    'coach.profile_image as coach_profile_image',
                    'master_country.country_name',
                    'master_state.state_name',
                    'master_city.city_name',
                    'enquiry.id',
                    'enquiry.enquiry_title',
                    'enquiry.enquiry_detail'
                )
                ->where('enquiry.id', $id)
                ->where('coach.user_type', 3)
                ->where('coach.user_status', 1)
                ->first();


            $enquiry_detail = DB::table('enquiry')
                ->join('users', 'users.id', '=', 'enquiry.user_id')
                ->select('users.*', 'enquiry.enquiry_title', 'enquiry.enquiry_detail')
                ->where('enquiry.id', $id)
                ->first();
        }

        return view('admin.view_coach_enquiry', compact('coach_detail', 'enquiry_detail', 'id'));
    }

    //  public function enquiry_status(Request $request)
    // {
    //     $user = MasterEnquiry::find($request->user);
    //     $user->enquiry_status=$request->status;
    //     $user->save();
    // }
}
