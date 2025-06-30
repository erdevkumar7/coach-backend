<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserService;
use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', 0); // only check for non-deleted users
                }),
            ],
            'password'   => 'required|string|min:6',
            'user_type' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'user_type'  => $request->user_type,
            'country_id' => $request->country_id,
            'user_timezone' => $request->user_timezone,
            'password'   => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => [
                'id'         => $user->id,
                'email'      => $user->email,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'user_type'  => $user->user_type,
                'country_id' => $user->country_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = $request->user_type;

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        if ($user) {
            if ($user->is_deleted) {
                return response()->json(['error' => 'User not found or deactivated'], 403);
            }
            return response()->json([
                'user' => [
                    'id'         => $user->id,
                    'email'      => $user->email,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'user_type'  => $user->user_type,
                    'country_id' => $user->country_id,
                    'user_timezone' => $user->user_timezone,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'token' => $token
            ]);
        } else {
            return response()->json(['message' => 'Invalid credentail']);
        }
    }


    public function me()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'id'         => $user->id,
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'user_type'  => $user->user_type,
            'country_id' => $user->country_id,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken());

            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, token invalid'], 500);
        }
    }


    public function index(Request $request)
    {

        // $authUser = JWTAuth::parseToken()->authenticate();

        // if ($authUser->user_type !== 2 && $authUser->user_type !== 3) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }

        $country  = DB::table('master_country')->where('country_status', 1)->get();
        $language = DB::table('master_language')->where('is_active', 1)->get();
        $service  = DB::table('master_service')->where('is_active', 1)->get();
        $type     = DB::table('coach_type')->where('is_active', 1)->get();
        $category = DB::table('coaching_cat')->where('is_active', 1)->get();
        $mode     = DB::table('delivery_mode')->where('is_active', 1)->get();

        $subtype = $user_detail = $state = $city = $profession = null;
        $selectedServiceIds = $selectedLanguageIds = [];

        $id = $request->input('user_id');
        if ($id) {

            $user_detail = DB::table('users')->where('id', $id)->first();

            if ($user_detail) {
                $state = DB::table('master_state')->where('state_country_id', $user_detail->country_id)->get();
                $city = DB::table('master_city')->where('city_state_id', $user_detail->state_id)->get();

                $profession = DB::table('user_professional')->where('user_id', $id)->first();
                if ($profession) {
                    $subtype = DB::table('coach_subtype')->where('coach_type_id', $profession->coach_type)->get();
                }

                $selectedServiceIds = UserService::where('user_id', $id)->pluck('service_id')->toArray();
                $selectedLanguageIds = UserLanguage::where('user_id', $id)->pluck('language_id')->toArray();
            }
        }

        $query = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city'
        ])
            ->where('users.user_type', 3)
            ->where('user_status', 1)
            ->orderBy('users.id', 'desc');


        // Paginate results
        $users = $query->paginate(10);

        // Format results
        $results = $users->getCollection()->map(function ($user) {
            return [
                'user_id'              => $user->id,
                'first_name'           => $user->first_name,
                'last_name'            => $user->last_name,
                'email'                => $user->email,
                'contact_number'       => $user->contact_number,
                'user_type'            => $user->user_type,
                'user_status'            => $user->user_status,
                'country_id'           => optional($user->country)->country_name ?? '',
                'is_deleted'           => $user->is_deleted,
                'is_active'            => $user->is_active,
                'email_verified'       => $user->email_verified,
                'professional_title'   => $user->professional_title ?? '',
                'detailed_bio'         => $user->detailed_bio ?? '',
                'short_bio'            => $user->short_bio ?? '',
                'user_timezone'        => $user->user_timezone ?? '',
                'gender'               => $user->gender ?? '',
                'is_paid'              => $user->is_paid ?? '',
                'state_id'             => optional($user->state)->state_name ?? '',
                'city_id'              => optional($user->city)->city_name ?? '',
                'verification_at'      => $user->verification_at,
                'verification_token'   => $user->verification_token,
                'reset_token'          => $user->reset_token,
                'created_at'           => $user->created_at,
                'updated_at'           => $user->updated_at,

                'coaching_category'    => optional($user->userProfessional)->coaching_category ?? '',
                'delivery_mode'        => optional($user->userProfessional)->delivery_mode ?? '',
                'free_trial_session'   => optional($user->userProfessional)->free_trial_session ?? '',
                'is_volunteered_coach' => optional($user->userProfessional)->is_volunteered_coach ?? '',
                'volunteer_coaching'   => optional($user->userProfessional)->volunteer_coaching ?? '',
                'video_link' => optional($user->userProfessional)->video_link ?? '',
                'experience'    => optional($user->userProfessional)->experience ?? '',
                'price'        =>  optional($user->userProfessional)->price ?? '',
                'website_link'   => optional($user->userProfessional)->website_link ?? '',
                'facebook_link'   => optional($user->userProfessional)->fb_link ?? '',
                'insta_link'   => optional($user->userProfessional)->insta_link ?? '',
                'linkdin_link'   => optional($user->userProfessional)->linkdin_link ?? '',
                'booking_link'   => optional($user->userProfessional)->booking_link ?? '',
                'objective' => optional($user->userProfessional)->website_link ?? '',
                'coach_type' => optional(optional($user->userProfessional)->coachType)->type_name ?? '',
                'coach_subtype' => optional(optional($user->userProfessional)->coachSubtype)->subtype_name ?? '',
                'profile_image'        => $user->profile_image
                    ? url('public/uploads/profile_image/' . $user->profile_image)
                    : '',
                'service_names' => $user->services->pluck('servicename')->pluck('service'),
                'language_names' => $user->languages->pluck('languagename')->pluck('language'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $results,
            'pagination' => [
                'total'        => $users->total(),
                'per_page'     => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
            ],
        ]);
    }

    public function coachDetails(Request $request)
    {
        $coach = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city'
        ])
            ->where('id', $request->id)
            ->where('user_status', 1)
            ->where('users.user_type', 3)
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found or inactive.',
            ], 404);
        }

        $getDocument = DB::table('user_document')
            ->select('id', 'document_file', 'original_name', 'document_type')
            ->where('user_id', $coach->id)
            ->get()
            ->map(function ($doc) {
                $doc->document_file = $doc->document_file
                    ? url('public/uploads/documents/' . $doc->document_file)
                    : null;
                return $doc;
            });
        // Format response
        $data = [
            'user_id'              => $coach->id,
            'first_name'           => $coach->first_name,
            'last_name'            => $coach->last_name,
            'email'                => $coach->email,
            'contact_number'       => $coach->contact_number,
            'user_type'            => $coach->user_type,
            'country_id'           => optional($coach->country)->country_name ?? '',
            'is_deleted'           => $coach->is_deleted,
            'is_active'            => $coach->is_active,
            'is_corporate'         => $coach->is_corporate,
            'is_verified'           => $coach->is_verified,
            'email_verified'       => $coach->email_verified,
            'professional_title'   => $coach->professional_title ?? '',
            'company_name'         => $coach->company_name ?? '',
            'exp_and_achievement'  => $coach->exp_and_achievement ?? '',
            'detailed_bio'         => $coach->detailed_bio ?? '',
            'short_bio'            => $coach->short_bio ?? '',
            'user_timezone'        => $coach->user_timezone ?? '',
            'gender'               => $coach->gender ?? '',
            'is_paid'              => $coach->is_paid ?? '',
            'state_id'             => optional($coach->state)->state_name ?? '',
            'city_id'              => optional($coach->city)->city_name ?? '',
            'verification_at'      => $coach->verification_at,
            'verification_token'   => $coach->verification_token,
            'reset_token'          => $coach->reset_token,
            'created_at'           => $coach->created_at,
            'updated_at'           => $coach->updated_at,

            'coaching_category'    => optional($coach->userProfessional)->coaching_category ?? '',
            'delivery_mode'        => optional(optional($coach->userProfessional)->deliveryMode)->mode_name ?? '',
            'free_trial_session'   => optional($coach->userProfessional)->free_trial_session ?? '',
            'is_volunteered_coach' => optional($coach->userProfessional)->is_volunteered_coach ?? '',
            'volunteer_coaching'   => optional($coach->userProfessional)->volunteer_coaching ?? '',
            'video_link' => optional($coach->userProfessional)->video_link ?? '',
            'experience'    => optional($coach->userProfessional)->experience ?? '',
            'price'        =>  optional($coach->userProfessional)->price ?? '',
            'website_link'   => optional($coach->userProfessional)->website_link ?? '',
            'facebook_link'   => optional($coach->userProfessional)->fb_link ?? '',
            'youtube_link'   => optional($coach->userProfessional)->youtube_link ?? '',
            'podcast_link'   => optional($coach->userProfessional)->podcast_link ?? '',
            'insta_link'   => optional($coach->userProfessional)->insta_link ?? '',
            'linkdin_link'   => optional($coach->userProfessional)->linkdin_link ?? '',
            'booking_link'   => optional($coach->userProfessional)->booking_link ?? '',
            'objective' => optional($coach->userProfessional)->website_link ?? '',
            'coach_type' => optional(optional($coach->userProfessional)->coachType)->type_name ?? '',
            'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->subtype_name ?? '',
            'profile_image'        => $coach->profile_image
                ? url('public/uploads/profile_image/' . $coach->profile_image)
                : '',
            'service_names' => $coach->services->pluck('servicename')->pluck('service'),
            'language_names' => $coach->languages->pluck('languagename')->pluck('language'),
            'user_documents' => $getDocument ?? [],
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }


    public function getuserprofile(Request $request)
    {

        $authUser = JWTAuth::parseToken()->authenticate();

        $id = $authUser->id;

        if (!$authUser) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid or missing token.',
            ], 401);
        }
        // Fetch coach with relationships
        $coach = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city'
        ])
            ->where('id', $id)
            ->where('user_status', 1)
            ->whereIn('users.user_type', [2, 3])
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found or inactive.',
            ], 404);
        }

        // Format response
        $data = [
            'user_id'              => $coach->id,
            'first_name'           => $coach->first_name,
            'last_name'            => $coach->last_name,
            'email'                => $coach->email,
            'contact_number'       => $coach->contact_number,
            'user_type'            => $coach->user_type,
            'display_name'         => $coach->display_name ?? '',
            'country_id'           => $coach->country_id ?? '',
            'is_deleted'           => $coach->is_deleted,
            'is_active'            => $coach->is_active,
            'email_verified'       => $coach->email_verified,
            'professional_title'   => $coach->professional_title ?? '',
            'company_name'         => $coach->company_name ?? '',
            'professional_profile' => $coach->professional_profile ?? '',
            'detailed_bio'         => $coach->detailed_bio ?? '',
            'short_bio'            => $coach->short_bio ?? '',
            'user_timezone'        => $coach->user_timezone ?? '',
            'gender'               => $coach->gender ?? '',
            'is_paid'              => $coach->is_paid ?? '',
            'state_id'             => $coach->state_id ?? '',
            'city_id'              => $coach->city_id ?? '',
            'verification_at'      => $coach->verification_at,
            'verification_token'   => $coach->verification_token,
            'reset_token'          => $coach->reset_token,
            'created_at'           => $coach->created_at,
            'updated_at'           => $coach->updated_at,

            'coaching_category'    => optional($coach->userProfessional)->coaching_category ?? '',
            'delivery_mode'        => optional($coach->userProfessional)->delivery_mode ?? '',
            'free_trial_session'   => optional($coach->userProfessional)->free_trial_session ?? '',
            'is_volunteered_coach' => optional($coach->userProfessional)->is_volunteered_coach ?? '',
            'volunteer_coaching'   => optional($coach->userProfessional)->volunteer_coaching ?? '',
            'video_link' => optional($coach->userProfessional)->video_link ?? '',
            'experience'    => optional($coach->userProfessional)->experience ?? '',
            'price'        =>  optional($coach->userProfessional)->price ?? '',
            'website_link'   => optional($coach->userProfessional)->website_link ?? '',
            'facebook_link'   => optional($coach->userProfessional)->fb_link ?? '',
            'insta_link'   => optional($coach->userProfessional)->insta_link ?? '',
            'linkdin_link'   => optional($coach->userProfessional)->linkdin_link ?? '',
            'booking_link'   => optional($coach->userProfessional)->booking_link ?? '',
            'objective' => optional($coach->userProfessional)->website_link ?? '',
            'coach_type' => optional(optional($coach->userProfessional)->coachType)->id ?? '',
            'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->id ?? '',
            'age_group'        =>  optional($coach->userProfessional)->age_group ?? '',
            'profile_image'        => $coach->profile_image
                ? url('public/uploads/profile_image/' . $coach->profile_image)
                : '',
            'service_names' => $coach->services->pluck('servicename')->pluck('service'),
            // 'language_names' => $coach->languages->pluck('languagename')->pluck('language'),
            'language_names' => $coach->languages->map(function ($lang) {
                return [
                    'id' => $lang->languagename->id,
                    'language' => $lang->languagename->language,
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function getcoachprofile(Request $request)
    {
        // Fetch coach with relationships
        $coach = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city'
        ])
            ->where('id', $request->id)
            ->where('user_status', 1)
            ->where('users.user_type', 3)
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found or inactive.',
            ], 404);
        }

        // Format response
        $data = [
            'user_id'              => $coach->id,
            'first_name'           => $coach->first_name,
            'last_name'            => $coach->last_name,
            'email'                => $coach->email,
            'user_type'            => $coach->user_type,
            'country_id'           => optional($coach->country)->country_name ?? '',
            'is_deleted'           => $coach->is_deleted,
            'is_active'            => $coach->is_active,
            'email_verified'       => $coach->email_verified,
            'professional_title'   => $coach->professional_title ?? '',
            'detailed_bio'         => $coach->detailed_bio ?? '',
            'short_bio'            => $coach->short_bio ?? '',
            'user_timezone'        => $coach->user_timezone ?? '',
            'gender'               => $coach->gender ?? '',
            'is_paid'              => $coach->is_paid ?? '',
            'state_id'             => optional($coach->state)->state_name ?? '',
            'city_id'              => optional($coach->city)->city_name ?? '',
            'verification_at'      => $coach->verification_at,
            'verification_token'   => $coach->verification_token,
            'reset_token'          => $coach->reset_token,
            'created_at'           => $coach->created_at,
            'updated_at'           => $coach->updated_at,

            'coaching_category'    => optional($coach->userProfessional)->coaching_category ?? '',
            'delivery_mode'        => optional($coach->userProfessional)->delivery_mode ?? '',
            'free_trial_session'   => optional($coach->userProfessional)->free_trial_session ?? '',
            'is_volunteered_coach' => optional($coach->userProfessional)->is_volunteered_coach ?? '',
            'volunteer_coaching'   => optional($coach->userProfessional)->volunteer_coaching ?? '',
            'video_link' => $coach->video_link ?? '',
            'experience'    => $coach->experience ?? '',
            'price'        => $coach->price ?? '',
            'website_link'   => $coach->website_link ?? '',
            'objective' => $coach->objective ?? '',
            'coach_type' => optional(optional($coach->userProfessional)->coachType)->type_name ?? '',
            'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->subtype_name ?? '',
            'profile_image'        => $coach->profile_image
                ? url('public/uploads/profile_image/' . $coach->profile_image)
                : '',
            'service_names' => $coach->services->pluck('servicename')->pluck('service'),
            'language_names' => $coach->languages->pluck('languagename')->pluck('language'),
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function updateProfile(Request $request)
    {

        $coach = Auth::user(); //  JWT Authenticated User

        $id = $coach->id;

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or inactive.',
            ], 403);
        }
        //   $id = $request->id;

        $coach = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city'
        ])
            ->where('id', $id)
            ->where('user_status', 1)
            ->where('user_type', $request->user_type)
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found or inactive.',
            ], 404);
        }

        $coach->first_name = $request->first_name;
        $coach->last_name = $request->last_name;
        $coach->email = $request->email;
        $coach->display_name = $request->display_name;
        $coach->professional_profile = $request->professional_profile;
        $coach->professional_title = $request->professional_title;
        $coach->contact_number = $request->contact_number;
        $coach->user_type = $request->user_type;
        $coach->country_id = $request->country_id;
        $coach->gender = $request->gender;
        $coach->state_id = $request->state_id;
        $coach->city_id = $request->city_id;
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/uploads/profile_image'), $imageName);
            $coach->profile_image = $imageName;
        }

        if ($request->filled('password')) {
            $coach->password = Hash::make($request->password);
        }

        $coach->save();
        if ($coach->userProfessional) {
            $coach->userProfessional->coaching_category = $request->coaching_category;
            $coach->userProfessional->delivery_mode = $request->delivery_mode;
            $coach->userProfessional->free_trial_session = $request->free_trial_session;
            $coach->userProfessional->is_volunteered_coach = $request->is_volunteered_coach;
            $coach->userProfessional->video_link = $request->video_link;
            $coach->userProfessional->experience = $request->experience;
            $coach->userProfessional->price = $request->price;
            $coach->userProfessional->website_link = $request->website_link;
            $coach->userProfessional->insta_link = $request->insta_link ?? '';
            $coach->userProfessional->fb_link = $request->fb_link ?? '';
            $coach->userProfessional->linkdin_link = $request->linkdin_link ?? '';
            $coach->userProfessional->booking_link = $request->booking_link ?? '';
            $coach->userProfessional->coach_type = $request->coach_type;
            $coach->userProfessional->coach_subtype = $request->coach_subtype;
            $coach->userProfessional->save();
        }

        if ($request->service_offered) {
            $newServiceIds = $request->input('service_offered', []);

            $existingServiceIds = UserService::where('user_id', $id)
                ->pluck('service_id')
                ->toArray();

            $toDelete = array_diff($existingServiceIds, $newServiceIds);

            // Find services to add
            $toAdd = array_diff($newServiceIds, $existingServiceIds);

            // Delete unselected services
            UserService::where('user_id', $id)
                ->whereIn('service_id', $toDelete)
                ->delete();

            // Add new services
            foreach ($toAdd as $serviceId) {
                UserService::create([
                    'user_id' => $id,
                    'service_id' => $serviceId,
                ]);
            }
        }

        if ($request->language) {
            $newlangIds = $request->input('language', []);

            $existingLanguageIds = UserLanguage::where('user_id', $id)
                ->pluck('language_id')
                ->toArray();

            // Find services to remove
            $toDeletel = array_diff($existingLanguageIds, $newlangIds);

            // Find services to add
            $toAddl = array_diff($newlangIds, $existingLanguageIds);

            // Delete unselected services
            UserLanguage::where('user_id', $id)
                ->whereIn('language_id', $toDeletel)
                ->delete();

            // Add new services
            foreach ($toAddl as $languageId) {
                UserLanguage::create([
                    'user_id' => $id,
                    'language_id' => $languageId,
                ]);
            }
        }
        $coach->load([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city'
        ]);

        // Prepare response data
        $data = [
            'user_id' => $coach->id,
            'first_name' => $coach->first_name,
            'last_name' => $coach->last_name,
            'email' => $coach->email,
            'display_name' => $coach->display_name ?? '',
            'professional_profile' => $coach->professional_profile ?? '',
            'professional_title'   => $coach->professional_title ?? '',
            'contact_number' => $coach->contact_number ?? '',
            'user_type' => $coach->user_type,
            'password' => $coach->password,
            'country' => optional($coach->country)->country_name,
            'state' => optional($coach->state)->state_name,
            'city' => optional($coach->city)->city_name,
            'coaching_category' => optional($coach->userProfessional)->coaching_category,
            'delivery_mode' => optional($coach->userProfessional)->delivery_mode,
            'free_trial_session' => optional($coach->userProfessional)->free_trial_session,
            'is_volunteered_coach' => optional($coach->userProfessional)->is_volunteered_coach,
            'video_link' => optional($coach->userProfessional)->video_link,
            'experience' => optional($coach->userProfessional)->experience,
            'price' => optional($coach->userProfessional)->price,
            'website_link' => optional($coach->userProfessional)->website_link,
            'facebook_link'   => optional($coach->userProfessional)->fb_link ?? '',
            'insta_link'   => optional($coach->userProfessional)->insta_link ?? '',
            'linkdin_link'   => optional($coach->userProfessional)->linkdin_link ?? '',
            'booking_link'   => optional($coach->userProfessional)->booking_link ?? '',
            'coach_type' => optional(optional($coach->userProfessional)->coachType)->type_name,
            'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->subtype_name,
            'profile_image'        => $coach->profile_image
                ? url('public/uploads/profile_image/' . $coach->profile_image)
                : '',
            'service_names' => $coach->services->pluck('servicename.service'),
            'language_names' => $coach->languages->pluck('languagename.language'),

        ];

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $data,
        ]);
    }
}
