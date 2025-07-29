<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserService;
use App\Models\UserDocument;
use App\Models\UserLanguage;
use App\Models\UserServicePackage;
use App\Models\UserSubscription;
use App\Models\FavoriteCoach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Mail;

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

        $data = [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'user_id'    => $user->id,
        ];
        Mail::send('emails.signup_template', $data, function ($message) use ($user) {
                // $message->from('your-email@example.com', 'Your App Name');
                $message->to($user->email);
                $message->subject('Coach Sparkle - Account E-mail Verification');
            });
    
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
            if ($user->is_deleted != 0) {
                return response()->json(['error' => 'User not found or deactivated'], 403);
            }
            if ($user->email_verified != 1) {
                return response()->json(['error' => 'Please check your email for a verification link'], 403);
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


    public function change_user_status(Request $request)
    {
        // echo "test";die;
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
      
        $user->email_verified = 1;
        $user->save();

        return redirect()->away('https://votivereact.in/coachsparkle/login');
    }

    
  public function validateToken()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $data = [
            'id'         => $user->id,
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'user_type'  => $user->user_type,
            'country_id' => $user->country_id,
            'profile_image'        => $user->profile_image
                ? url('public/uploads/profile_image/' . $user->profile_image)
                : '',
        ];

        return response()->json([
            'success' => true,
            'data'    =>  $data
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


    public function coachlist(Request $request)
    {

        // $authUser = JWTAuth::parseToken()->authenticate();

        // if ($authUser->user_type !== 2 && $authUser->user_type !== 3) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }

        // $country  = DB::table('master_country')->where('country_status', 1)->get();
        // $language = DB::table('master_language')->where('is_active', 1)->get();
        // $service  = DB::table('master_service')->where('is_active', 1)->get();
        // $type     = DB::table('coach_type')->where('is_active', 1)->get();
        // $category = DB::table('coaching_cat')->where('is_active', 1)->get();
        // $mode     = DB::table('delivery_mode')->where('is_active', 1)->get();

        // $subtype = $user_detail = $state = $city = $profession = null;
        // $selectedServiceIds = $selectedLanguageIds = [];

        // $id = $request->input('user_id');
        // if ($id) {

        //     $user_detail = DB::table('users')->where('id', $id)->first();

        //     if ($user_detail) {
        //         $state = DB::table('master_state')->where('state_country_id', $user_detail->country_id)->get();
        //         $city = DB::table('master_city')->where('city_state_id', $user_detail->state_id)->get();

        //         $profession = DB::table('user_professional')->where('user_id', $id)->first();
        //         if ($profession) {
        //             $subtype = DB::table('coach_subtype')->where('coach_type_id', $profession->coach_type)->get();
        //         }

        //         $selectedServiceIds = UserService::where('user_id', $id)->pluck('service_id')->toArray();
        //         $selectedLanguageIds = UserLanguage::where('user_id', $id)->pluck('language_id')->toArray();
        //     }
        // }


        $delivery_mode = 2; //$request->preferred_mode_of_delivery; //
        $free_trial_session = 1;
        $is_corporate = 1;
        $countries = [101, 4, 5];
        $coaching_categories = [1, 2];

        $query = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city',
            'reviews'
        ])
            ->where('users.user_type', 3)
            ->where('user_status', 1);

        // // Is corporate filter
        // if (isset($is_corporate)) {
        //     $query->where('users.is_corporate', $is_corporate);
        // }

        // // Countries filter
        // if (isset($countries)) {
        //     $query->whereIn('users.country_id', $countries);
        // }

        // Coach Category filter , Coach type
        // if (isset($coaching_categories)) {
        //     $query->whereHas('userProfessional', function ($q) use ($coaching_categories) {
        //         $q->whereIn('coach_type', $coaching_categories);
        //     });
        // }

        // // Devivery mode filter
        // if (isset($delivery_mode)) {
        //     $query->whereHas('userProfessional', function ($q) use ($delivery_mode) {
        //         $q->where('delivery_mode', $delivery_mode);
        //     });
        // }

        // // Free trail filter
        // if (isset($free_trial_session)) {
        //     $query->whereHas('userProfessional', function ($q) use ($free_trial_session) {
        //         $q->where('free_trial_session', $free_trial_session);
        //     });
        // }




        // Step 3: Add order and get results
        $query->orderBy('users.id', 'desc');


        // Paginate results
        $users = $query->paginate(10);

        // Format results
        $results = $users->getCollection()->map(function ($user) {


            // Get service package of coach
            $UserServicePackage = UserServicePackage::where('coach_id', $user->id)
                //->select('title', 'package_status', 'short_description', 'coaching_category', 'description')
                ->get();


            // Favorite status update 0/1
            $authUser = null;
            $loginuser_id = null;
            $fav_coach_ids = [];

            try {
                if ($token = JWTAuth::getToken()) {
                    $authUser = JWTAuth::parseToken()->authenticate();
                    $loginuser_id = $authUser->id;

                    $fav_coach_ids = DB::table('favorite_coach')
                        ->where('user_id', $loginuser_id)
                        ->pluck('coach_id')
                        ->toArray();
                }
            } catch (\Exception $e) {
                // No token or invalid token, proceed as guest
                $authUser = null;
            }


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
                'video_link'            => optional($user->userProfessional)->video_link ?? '',
                'experience'            => optional($user->userProfessional)->experience ?? '',
                'price'                 =>  optional($user->userProfessional)->price ?? '',
                'website_link'          => optional($user->userProfessional)->website_link ?? '',
                'facebook_link'         => optional($user->userProfessional)->fb_link ?? '',
                'insta_link'            => optional($user->userProfessional)->insta_link ?? '',
                'linkdin_link'          => optional($user->userProfessional)->linkdin_link ?? '',
                'blog_article'          => optional($user->userProfessional)->blog_article ?? '',
                'objective'             => optional($user->userProfessional)->website_link ?? '',
                'coach_type'            => optional(optional($user->userProfessional)->coachType)->type_name ?? '',
                'coach_subtype'         => optional(optional($user->userProfessional)->coachSubtype)->subtype_name ?? '',
                'profile_image'        => $user->profile_image
                    ? url('public/uploads/profile_image/' . $user->profile_image)
                    : '',
                'service_names'         => $user->services->pluck('servicename')->pluck('service'),
                'language_names'        => $user->languages->pluck('languagename')->pluck('language'),
                // new fields
                'is_verified'           => $user->is_verified,
                'price_range'           =>  optional($user->userProfessional)->price_range ?? '',
                'is_corporate'          => $user->is_corporate,
                'is_fevorite'           => in_array($user->id, $fav_coach_ids) ? 1 : 0,
                'totalReviews'          => $user->reviews->count(),
                'averageRating'         => $user->reviews->avg('rating'),
                'packages'              => $UserServicePackage
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

    public function date_time_avalibility(){
        echo "test";die;
    }
    public function coachDetails(Request $request)
    {
        $coach_id = $request->id;
        //return "Id is= ".$coach_id;
        $coach = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city',
            'userServicePackages',
            'reviews',
            'coachSubtypes'
        ])
            ->where('id', $coach_id)
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

        $UserServicePackage = UserServicePackage::with([
            'deliveryMode:id,mode_name',
            'sessionFormat:id,name,description',
            'priceModel:id,name,description',
        ])->where('coach_id', $coach->id)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Append media_url if media exists
        $UserServicePackage->transform(function ($package) {
            if ($package->media_file) {
                $package->media_file = url('public/uploads/service_packages/' . $package->media_file);
            } else {
                $package->media_file = null;
            }
            return $package;
        });

        $coachSubtypesData = $coach->coachSubtypes->map(function ($subtype) {
            return [
                'id' => $subtype->id,
                'subtype_name' => $subtype->subtype_name,
            ];
        });


        // Favorite status update 0/1
        $authUser = null;
        $loginuser_id = null;
        $fav_coach_ids = [];

        try {
            if ($token = JWTAuth::getToken()) {
                $authUser = JWTAuth::parseToken()->authenticate();
                $loginuser_id = $authUser->id;

                $fav_coach_ids = DB::table('favorite_coach')
                    ->where('user_id', $loginuser_id)
                    ->pluck('coach_id')
                    ->toArray();
            }
        } catch (\Exception $e) {
            // No token or invalid token, proceed as guest
            $authUser = null;
        }


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
            'age_group'        =>  optional(optional($coach->userProfessional)->ageGroup)->age_range ?? '',
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
            'blog_article'   => optional($coach->userProfessional)->blog_article ?? '',
            'objective' => optional($coach->userProfessional)->website_link ?? '',

            // new fields
            'is_verified'           => $coach->is_verified,
            'price_range'           =>  optional($coach->userProfessional)->price_range ?? '',
            'is_corporate'          => $coach->is_corporate,
            'is_fevorite'           => in_array($coach->id, $fav_coach_ids) ? 1 : 0,
            'totalReviews'          => $coach->reviews->count(),
            'averageRating'         => $coach->reviews->avg('rating'),

            'coach_type' => optional(optional($coach->userProfessional)->coachType)->type_name ?? '',
            // 'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->subtype_name ?? '',
            'coach_subtype' => $coachSubtypesData ?? [],
            'profile_image'        => $coach->profile_image
                ? url('public/uploads/profile_image/' . $coach->profile_image)
                : '',
            'service_names' => $coach->services->pluck('servicename')->pluck('service'),
            'language_names' => $coach->languages->pluck('languagename')->pluck('language'),
            'user_documents' => $getDocument ?? [],
            'service_packages' => $UserServicePackage ?? [],
            // 'service_packages' => $coach->userServicePackages->map(function ($pkg) {
            //     return [
            //         'id'            => $pkg->id,
            //         'title'         => $pkg->title,
            //         'price'         => $pkg->price,
            //         'delivery_mode' => $pkg->deliveryMode->mode_name ?? null,
            //         'session_format' => $pkg->sessionFormat ? [
            //             'name' => $pkg->sessionFormat->name,
            //             'description' => $pkg->sessionFormat->description,
            //         ] : null,
            //         'price_model' => $pkg->priceModel->name ?? null,
            //     ];
            // }),
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


        // Get subscription plan details
        $subscription = UserSubscription::with('subscription_plan')
            // ->where('is_deleted', 0)
            // ->where('is_active', 0)
            ->where('user_id', $id)->first();



        // Prepare subscription plan array
        $subscription_plan = [
            'id'        => $subscription->id ?? '',
            'plan_id'   => $subscription->plan_id ?? '',
            'amount'    => $subscription->amount ?? '',
            'plan_name' => $subscription->subscription_plan->plan_name ?? '',
        ];



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
            'exp_and_achievement' => $coach->exp_and_achievement ?? '',
            'short_bio'            => $coach->short_bio ?? '',
            'user_timezone'        => $coach->user_timezone ?? '',
            'gender'               => $coach->gender ?? '',
            'is_paid'              => $coach->is_paid ?? '',
            'is_corporate'         => $coach->is_corporate,
            'state_id'             => $coach->state_id ?? '',
            'city_id'              => $coach->city_id ?? '',
            'verification_at'      => $coach->verification_at,
            'verification_token'   => $coach->verification_token,
            'reset_token'          => $coach->reset_token,
            'created_at'           => $coach->created_at,
            'updated_at'           => $coach->updated_at,
            'subscription_plan'  => $subscription_plan,
            'coaching_category'    => optional($coach->userProfessional)->coaching_category ?? '',
            'delivery_mode'        => optional($coach->userProfessional)->delivery_mode ?? '',
            'free_trial_session'   => optional($coach->userProfessional)->free_trial_session ?? '',
            'is_volunteered_coach' => optional($coach->userProfessional)->is_volunteered_coach ?? '',
            'volunteer_coaching'   => optional($coach->userProfessional)->volunteer_coaching ?? '',
            'video_link' => optional($coach->userProfessional)->video_link ?? '',
            'experience'    => optional($coach->userProfessional)->experience ?? '',
            'price'        =>  optional($coach->userProfessional)->price ?? '',
            'price_range'        =>  optional($coach->userProfessional)->price_range ?? '',
            'is_pro_bono'   => optional($coach->userProfessional)->is_pro_bono ?? '',

            'linkdin_link'   => optional($coach->userProfessional)->linkdin_link ?? '',
            'website_link'   => optional($coach->userProfessional)->website_link ?? '',
            'youtube_link'   => optional($coach->userProfessional)->youtube_link ?? '',
            'podcast_link'   => optional($coach->userProfessional)->podcast_link ?? '',
            'blog_article'   => optional($coach->userProfessional)->blog_article ?? '',

            // 'facebook_link'   => optional($coach->userProfessional)->fb_link ?? '',
            // 'insta_link'   => optional($coach->userProfessional)->insta_link ?? '',

            'coach_type' => optional(optional($coach->userProfessional)->coachType)->id ?? '',
            'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->id ?? '',
            'age_group'        =>  optional($coach->userProfessional)->age_group ?? '',
            'profile_image'        => $coach->profile_image
                ? url('public/uploads/profile_image/' . $coach->profile_image)
                : '',
            //'service_names' => $coach->services->pluck('servicename')->pluck('service'),

            'service_keyword' => $coach->services->map(function ($srvc) {
                return [
                    'id' => $srvc->servicename->id,
                    'service' => $srvc->servicename->service,
                ];
            }),

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
        //return $id;
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
            // ->where('user_type', $request->user_type)
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or inactive.',
            ], 404);
        }


        // profile_image
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();

            // Delete old profile image if exists
            if ($coach->profile_image && file_exists(public_path('/uploads/profile_image/' . $coach->profile_image))) {
                unlink(public_path('/uploads/profile_image/' . $coach->profile_image));
            }

            // Upload new image
            $image->move(public_path('/uploads/profile_image'), $imageName);

            // Update DB field
            $coach->profile_image = $imageName;
        }


        $coach->first_name = $request->first_name;
        $coach->last_name = $request->last_name;
        $coach->email = $request->email;
        $coach->country_id = $request->country_id;
        $coach->state_id = $request->state_id;
        $coach->city_id = $request->city_id;
        $coach->gender = $request->gender;
        $coach->professional_title = $request->professional_title;
        $coach->company_name = $request->company_name;
        // expireance fld added in userProfessional tbl
        $coach->exp_and_achievement = $request->exp_and_achievement;
        $coach->detailed_bio = $request->detailed_bio;
        // Service kwyword add karna hai.



        // Yes, I am available for corporate coaching or training projects.
        $coach->is_corporate = $request->is_corporate ?? 0; // doute:  Is Pro Bono Coach


        $coach->save();
        if ($coach->userProfessional) {
            $coach->userProfessional->experience = $request->experience;
            $coach->userProfessional->coaching_category = $request->coaching_category;
            $coach->userProfessional->coach_subtype = $request->coach_subtype; // sub coching category
            $coach->userProfessional->delivery_mode = $request->delivery_mode;
            $coach->userProfessional->price = $request->price; // average_charge_hour add in price in db table
            $coach->userProfessional->price_range = $request->price_range; // price range
            $coach->userProfessional->age_group = $request->age_group;
            $coach->userProfessional->free_trial_session = $request->free_trial_session;
            $coach->userProfessional->is_pro_bono = $request->is_pro_bono; // doute:  Is Pro Bono Coach


            // social links
            $coach->userProfessional->linkdin_link = $request->linkdin_link ?? '';
            $coach->userProfessional->website_link = $request->website_link ?? '';
            $coach->userProfessional->youtube_link = $request->youtube_link;
            $coach->userProfessional->podcast_link = $request->podcast_link;
            $coach->userProfessional->blog_article = $request->blog_article; // Blog/Published Articles



            // Video upload
            if ($request->hasFile('video_link')) {
                $image = $request->file('video_link');
                $fileName = time() . '.' . $image->getClientOriginalExtension();

                // Delete old video if exists
                $oldVideo = $coach->userProfessional->video_link;
                if ($oldVideo && file_exists(public_path('/uploads/coach_video/' . $oldVideo))) {
                    unlink(public_path('/uploads/coach_video/' . $oldVideo));
                }

                // Move new video
                $image->move(public_path('/uploads/coach_video'), $fileName);

                // Update DB field
                $coach->userProfessional->video_link = $fileName;
            }



            if ($request->hasFile('upload_credentials')) {
                // 1. Delete old images from DB and file system
                $oldDocs = UserDocument::where('user_id', $id)->where('document_type', 1)->get();

                foreach ($oldDocs as $doc) {
                    $filePath = public_path('/uploads/documents/' . $doc->document_file);
                    if (file_exists($filePath)) {
                        unlink($filePath); // delete file from filesystem
                    }
                    $doc->delete(); // delete DB record
                }

                // 2. Upload new images
                foreach ($request->file('upload_credentials') as $file) {
                    $fileName = 'credential_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads/documents'), $fileName);

                    $gallery_update = UserDocument::create([
                        'user_id'        => $id,
                        'document_file'  => $fileName,
                        'original_name'  => $file->getClientOriginalName(),
                        'document_type'  => 1,
                    ]);

                    if (!$gallery_update) {
                        return response()->json(['message' => 'Document images not uploaded'], 500);
                    }
                }
            }

            $coach->userProfessional->save();

            // Services Add&Update
            if ($request->service_keyword) {

                $newServiceIds = $request->input('service_keyword', []);
                $existingServiceIds = UserService::where('user_id', $id)
                    ->pluck('service_id')
                    ->toArray();

                $toDelete = array_diff($existingServiceIds, $newServiceIds);
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

            // languages Add&Update
            if ($request->language_names) {
                $newlanguages = $request->input('language_names', []);
                $existinglanguages = UserLanguage::where('user_id', $id)
                    ->pluck('language_id')
                    ->toArray();

                $toDelete = array_diff($existinglanguages, $newlanguages);
                $toAdd = array_diff($newlanguages, $existinglanguages);

                // Delete unselected services
                UserLanguage::where('user_id', $id)
                    ->whereIn('language_id', $toDelete)
                    ->delete();

                // Add new services
                foreach ($toAdd as $languageId) {
                    UserLanguage::create([
                        'user_id' => $id,
                        'language_id' => $languageId,
                    ]);
                }
            }
        }

        $data = [
            'id'                   => $id,
            'first_name'           => $request->first_name,
            'last_name'            => $request->last_name,
            'email'                => $request->email,
            'country_id'           => $request->country_id,
            'city_id'              => $request->city_id,
            'gender'               => $request->gender,
            'professional_title'   => $request->professional_title,
            'company_name'         => $request->company_name,
            'exp_and_achievement'  => $request->exp_and_achievement,
            'detailed_bio'         => $request->detailed_bio,
            'is_corporate'         => $request->is_corporate ?? 0,
            'experience'           => $request->experience,
            'coaching_category'    => $request->coaching_category,
            'coach_subtype'        => $request->coach_subtype,
            'delivery_mode'        => $request->delivery_mode,
            'average_charge_hour'  => $request->average_charge_hour,
            'price_range'          => $request->price_range,
            'age_group'            => $request->age_group,
            'free_trial_session'   => $request->free_trial_session,
            'is_pro_bono'          => $request->is_pro_bono,
            'linkdin_link'         => $request->linkdin_link ?? '',
            'website_link'         => $request->website_link ?? '',
            'youtube_link'         => $request->youtube_link,
            'podcast_link'         => $request->podcast_link,
            'blog_article'         => $request->blog_article,
            'service_keyword'      => $request->service_keyword ?? [],
            'language'             => $request->language ?? [],
        ];



        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $data,
        ]);
    }
}