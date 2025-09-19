<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\UserService;
use App\Models\UserDocument;
use App\Models\UserLanguage;
use App\Models\CoachSubTypeUser;
use App\Models\CoachSubType;
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

            Setting::create([
            'user_id'                       => $user->id,
            'new_coach_match_alert'        => true,
            'message_notifications'        => true,
            'booking_reminders'            => true,
            'coaching_request_status'      => true,
            'platform_announcements'       => true,
            'blog_article_recommendations' => true,
            'billing_updates'              => true,
            'communication_preference'     => 'email',
            'profile_visibility'           => 'public',
            'allow_ai_matching'            => true,
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

        // Build user payload
        $userData = [
            'id'           => $user->id,
            'email'        => $user->email,
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'user_type'    => $user->user_type,
            'country_id'   => $user->country_id,
            'user_timezone'=> $user->user_timezone,
            'created_at'   => $user->created_at,
            'updated_at'   => $user->updated_at,
        ];

        // Return JSON + set token cookie
        return response()->json([
            'user'  => $userData,
            'token' => $token, // keep this if your frontend still uses it
        ])->cookie(
            'token',   // cookie name
            $token,    // cookie value
            60 * 24 * 7, // minutes = 7 days
            '/',       // path
            null,      // domain (null = current domain)
            true,      // secure (true = only HTTPS, set false for local dev if needed)
            true       // httpOnly (true = JS can't access cookie, only server)
        );
    } else {
        return response()->json(['message' => 'Invalid credential']);
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

             // Get subscription plan details
        $subscription = UserSubscription::with('subscription_plan')
            // ->where('is_deleted', 0)
            // ->where('is_active', 0)
            ->where('user_id', $user->id)->first();



        // Prepare subscription plan array
        $subscription_plan = [
            'id'        => $subscription->id ?? '',
            'plan_id'   => $subscription->plan_id ?? '',
            'amount'    => $subscription->amount ?? '',
            'plan_name' => $subscription->subscription_plan->plan_name ?? '',
        ];

        $data = [
            'id'         => $user->id,
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'user_type'  => $user->user_type,
            'country_id' => $user->country_id,
            'subscription_plan'  => $subscription_plan,
            'profile_image'        => $user->profile_image
                ? url('public/uploads/profile_image/' . $user->profile_image)
                : '',
        ];

        return response()->json([
            'success' => true,
            'data'    =>  $data
        ]);
    }
  public function validateToken12()
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

        $perPage = $request->input('per_page', 10) ; 
        $page = $request->input('page', $request->page) ?? 1;
        $query = User::with([
            'services',
            'languages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'country',
            'state',
            'city',
            'reviews',
            'coachsubtypeuser',
            'userServicePackages'
        ])
            ->where('users.user_type', 3)
            ->where('user_status', 1);

        $is_corporate = $request->is_corporate;
        // Is corporate filter
        if (isset($is_corporate)) {
            $query->where('users.is_corporate', $is_corporate);
        }

        // // Countries filter
        if (isset($request->countries)) {
            $query->whereIn('users.country_id', $request->countries);
        }

        $coaching_sub_categories = $request->coaching_sub_categories; 
        // Coach Category filter , Coach type
        // if (isset($coaching_sub_categories)) {
        //     $query->whereHas('userProfessional', function ($q) use ($coaching_sub_categories) {
        //         $q->whereIn('coach_subtype', $coaching_sub_categories);
        //     });
        // }

        if (isset($coaching_sub_categories)) {
            $query->whereHas('coachsubtypeuser', function ($q) use ($coaching_sub_categories) {
                $q->whereIn('coach_subtype_id', $coaching_sub_categories);
            });
        }
    

        $delivery_mode = $request->delivery_mode;
        // // Devivery mode filter
        if (isset($delivery_mode)) {
            $query->whereHas('userProfessional', function ($q) use ($delivery_mode) {
                $q->where('delivery_mode', $delivery_mode);
            });
        }

        $search = $request->search_for;
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('users.professional_title', 'LIKE', '%' . $search . '%') // title in users table
                    ->orwhere('users.company_name', 'LIKE', '%' . $search . '%')
                    // ->orWhereHas('userProfessional', function ($q) use ($search) {
                    //     $q->where('company_name', 'LIKE', '%' . $search . '%'); // company name
                    // })
                    ->orWhereHas('services', function ($q) use ($search) {
                        $q->whereHas('servicename', function ($subQ) use ($search) {
                            $subQ->where('service', 'LIKE', '%' . $search . '%'); // service name
                        });
                    });
            });
        }

        $free_trial_session = $request->free_trial_session ;
        // // Free trail filter
        if (isset($free_trial_session)) {
            $query->whereHas('userProfessional', function ($q) use ($free_trial_session) {
                $q->where('free_trial_session','>=', $free_trial_session);
            });
        }

        $services = $request->services ;
        // // Free trail filter
        if (isset($services)) {
            $query->whereHas('services', function ($q) use ($services) {
                $q->whereIn('service_id', $services);
            });
        }

        $languages = $request->languages;
        // // Free trail filter
        if (isset($languages)) {
            $query->whereHas('languages', function ($q) use ($languages) {
                $q->whereIn('language_id', $languages);
            });
        }

        $price = $request->price;
        if (isset($price) && is_array($price) && count($price) === 2) {
            $minPrice = min($price);
            $maxPrice = max($price);

            $query->whereHas('userProfessional', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [$minPrice, $maxPrice]);
            });
        }


        
        $average = $request->average_rating;
        // echo $average;die;
        if (isset($average)) {
            $query->whereHas('reviews', function ($q) {
                $q->whereNotNull('rating');
            })
            ->withAvg('reviews as average_rating', 'rating')
            ->having('average_rating', '>=', $average);
        }

        $availability_start = $request->availability_start;
        $availability_end = $request->availability_end;
        if (!empty($availability_start) && !empty($availability_end)) {
            $query->whereHas('userServicePackages', function ($q) use ($availability_start, $availability_end) {
                $q->where('booking_availability_start', '<=', $availability_end)
                ->where('booking_availability_end', '>=', $availability_start);
            });
        }

        $query->where('users.is_deleted', 0);
        // Step 3: Add order and get results
        $query->orderBy('users.id', 'desc');
   

        // Paginate results
        $users = $query->paginate($perPage, ['*'], 'page', $page);

       //    print_r($users);die;
        // Format results
    $results = $users->getCollection()->map(function ($user) use ($request) {


            // Get service package of coach
            $UserServicePackage = UserServicePackage::where('coach_id', $user->id)
                //->select('title', 'package_status', 'short_description', 'coaching_category', 'description')
                ->get();

          $fav_coach_ids = DB::table('favorite_coach')
                ->where('user_id', $request->user_id)
                ->pluck('coach_id')
                ->toArray();
                                 
            // Favorite status update 0/1
            $authUser = null;
            $loginuser_id = null;



            return [
                'user_id'              => $user->id,
                'first_name'           => $user->first_name,
                'last_name'            => $user->last_name,
                'email'                => $user->email,
                'contact_number'       => $user->contact_number,
                'user_type'            => $user->user_type,
                'user_status'            => $user->user_status,
                'company_name'            => $user->company_name,
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
                'coaching_sub_category'    => optional($user->userProfessional)->coach_subtype ?? '',
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
                'price'                 => optional($user->userProfessional)->price ?? '',
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
            'coachSubtypes',
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
           
                // $authUser = JWTAuth::parseToken()->authenticate();
                // $loginuser_id = $authUser->id;

                // echo $loginuser_id;die;
                $fav_coach_ids = DB::table('favorite_coach')
                    ->where('user_id', $request->user_id)
                    ->pluck('coach_id')
                    ->toArray();
                    // print_r($fav_coach_ids);die;
            
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
        // $id = 72;

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
            'city',
            'coachsubtypeuser'
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
            'prefer_mode'              => $coach->delivery_mode ?? '',
            'coaching_goal_1'              => $coach->coaching_goal_1 ?? '',
            'coaching_goal_2'              => $coach->coaching_goal_2 ?? '',
            'coaching_goal_3'              => $coach->coaching_goal_3 ?? '',
            'prefer_coaching_timing'       => $coach->coaching_time ?? '',
            'age_group_user'               => $coach->age_group ?? '',
            'coaching_topics'              => $coach->coaching_topics ?? '',
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
            // 'coach_subtype' => optional(optional($coach->userProfessional)->coachSubtype)->id ?? '',
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
       'coach_subtype' => $coach->coachsubtypeuser->map(function ($subtype) {
        return [
            'id' => $subtype->coach_subtype_id,
            'name' => CoachSubType::find($subtype->coach_subtype_id)->subtype_name ?? null,
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
    
 public function updateUserProfile(Request $request)
{
    $user = Auth::user(); // Authenticated user

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.',
        ], 403);
    }

    $id = $user->id;

    
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
            ->first();

    // Validation
    $validator = Validator::make($request->all(), [
        'email'=> [
            'email',
            'max:255',
            Rule::unique('users')->where(function ($query) {
                return $query->where('is_deleted', 0);
            })->ignore($id),
        ],

    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Update User Basic Info
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->country_id = $request->country_id;
    $user->short_bio = $request->short_bio;
    $user->professional_title = $request->your_profession;
    $user->coaching_topics = $request->prefer_coaching_topic;
    $user->coaching_time = $request->prefer_coaching_time;
    $user->display_name = $request->display_name;
    $user->age_group = $request->age_group;
    $user->delivery_mode = $request->prefer_mode;
    $user->professional_profile = $request->professional_profile;
    // $user->coach_agreement = $request->prefer_coach_agreement;
    $user->coaching_goal_1 = $request->coaching_goal_1;
    $user->coaching_goal_2 = $request->coaching_goal_2;
    $user->coaching_goal_3 = $request->coaching_goal_3;
    $user->save();

    // Update Languages
    if ($request->has('language_names')) {
        $newLanguages = $request->input('language_names', []);
        $existingLanguages = UserLanguage::where('user_id', $id)->pluck('language_id')->toArray();

        $toDelete = array_diff($existingLanguages, $newLanguages);
        $toAdd = array_diff($newLanguages, $existingLanguages);

        // Remove unselected languages
        UserLanguage::where('user_id', $id)->whereIn('language_id', $toDelete)->delete();

        // Add new languages
        foreach ($toAdd as $languageId) {
            UserLanguage::create([
                'user_id' => $id,
                'language_id' => $languageId,
            ]);
        }
    }

              
    return response()->json([
        'success' => true,
        'message' => 'User profile updated successfully',
        'data' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'display_name' => $user->display_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'country_id' => $user->country_id,
            'professional_profile' => $user->professional_profile,
            'coaching_goal_1' => $user->coaching_goal_1,
            'coaching_goal_2' => $user->coaching_goal_2,
            'coaching_goal_3' => $user->coaching_goal_3,
            'short_bio' => $user->short_bio,
            'prefer_coaching_topic' => $user->coaching_topics ?? null,
            'your_profession' => $user->professional_title ?? null,
            'age_group' => $user->age_group ?? null,
            'prefer_mode' => $user->delivery_mode ?? null,
            'prefer_coaching_time' => $user->coaching_time ?? null,
            'language_ids' => $request->language_names ?? [],
        ]
    ]);
}

    // public function updateProfile(Request $request)
    // {

    //     $coach = Auth::user(); 


    //     $id = $coach->id;
    //     if (!$coach) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found or inactive.',
    //         ], 403);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'email' => [
    //             'required',
    //             'email',
    //             'max:255',

    //             Rule::unique('users')->where(function ($query) {
    //                 return $query->where('is_deleted', 0);
    //             })->ignore($id), 
    //         ],
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }
    //     $coach = User::with([
    //         'services',
    //         'languages',
    //         'userProfessional.coachType',
    //         'userProfessional.coachSubtype',
    //         'country',
    //         'state',
    //         'city'
    //     ])
    //         ->where('id', $id)
    //         ->where('user_status', 1)
    //         ->first();

    //     if (!$coach) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found or inactive.',
    //         ], 404);
    //     }


    //     if ($request->hasFile('profile_image')) {
    //         $image = $request->file('profile_image');
    //         $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
    //         if ($coach->profile_image && file_exists(public_path('/uploads/profile_image/' . $coach->profile_image))) {
    //             unlink(public_path('/uploads/profile_image/' . $coach->profile_image));
    //         }

    //         $image->move(public_path('/uploads/profile_image'), $imageName);
    //         $coach->profile_image = $imageName;
    //     }


    //     $coach->first_name = $request->first_name;
    //     $coach->last_name = $request->last_name;
    //     $coach->email = $request->email;
    //     $coach->country_id = $request->country_id;
    //     $coach->state_id = $request->state_id;
    //     $coach->city_id = $request->city_id;
    //     $coach->gender = $request->gender;
    //     $coach->professional_title = $request->professional_title;
    //     $coach->company_name = $request->company_name;
    //     $coach->exp_and_achievement = $request->exp_and_achievement;
    //     $coach->detailed_bio = $request->detailed_bio;
    //     $coach->is_corporate = $request->is_corporate ?? 0; 


    //     $coach->save();
    //     if ($coach->userProfessional) {
    //         $coach->userProfessional->experience = $request->experience;
    //         $coach->userProfessional->coaching_category = $request->coaching_category;
    //         $coach->userProfessional->delivery_mode = $request->delivery_mode;
    //         $coach->userProfessional->price = $request->price; 
    //         $coach->userProfessional->price_range = $request->price_range; 
    //         $coach->userProfessional->age_group = $request->age_group;
    //         $coach->userProfessional->coach_type = $request->coach_type;
    //         $coach->userProfessional->free_trial_session = $request->free_trial_session;
    //         $coach->userProfessional->is_pro_bono = $request->is_pro_bono; 
    //         $coach->userProfessional->linkdin_link = $request->linkdin_link ?? '';
    //         $coach->userProfessional->website_link = $request->website_link ?? '';
    //         $coach->userProfessional->youtube_link = $request->youtube_link;
    //         $coach->userProfessional->podcast_link = $request->podcast_link;
    //         $coach->userProfessional->blog_article = $request->blog_article; 



    //         if ($request->hasFile('video_link')) {
    //             $image = $request->file('video_link');
    //             $fileName = time() . '.' . $image->getClientOriginalExtension();
    //             $oldVideo = $coach->userProfessional->video_link;
    //             if ($oldVideo && file_exists(public_path('/uploads/coach_video/' . $oldVideo))) {
    //                 unlink(public_path('/uploads/coach_video/' . $oldVideo));
    //             }
    //             $image->move(public_path('/uploads/coach_video'), $fileName);
    //             $coach->userProfessional->video_link = $fileName;
    //         }



    //         if ($request->hasFile('upload_credentials')) {
    //             $oldDocs = UserDocument::where('user_id', $id)->where('document_type', 1)->get();
    //             foreach ($oldDocs as $doc) {
    //                 $filePath = public_path('/uploads/documents/' . $doc->document_file);
    //                 if (file_exists($filePath)) {
    //                     unlink($filePath); 
    //                 }
    //                 $doc->delete(); 
    //             }

    //             foreach ($request->file('upload_credentials') as $file) {
    //                 $fileName = 'credential_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('/uploads/documents'), $fileName);

    //                 $gallery_update = UserDocument::create([
    //                     'user_id'        => $id,
    //                     'document_file'  => $fileName,
    //                     'original_name'  => $file->getClientOriginalName(),
    //                     'document_type'  => 1,
    //                 ]);

    //                 if (!$gallery_update) {
    //                     return response()->json(['message' => 'Document images not uploaded'], 500);
    //                 }
    //             }
    //         }

    //         $coach->userProfessional->save();

    //         if ($request->service_keyword) {

    //             $newServiceIds = $request->input('service_keyword', []);
    //             $existingServiceIds = UserService::where('user_id', $id)
    //                 ->pluck('service_id')
    //                 ->toArray();

    //             $toDelete = array_diff($existingServiceIds, $newServiceIds);
    //             $toAdd = array_diff($newServiceIds, $existingServiceIds);

    //             UserService::where('user_id', $id)
    //                 ->whereIn('service_id', $toDelete)
    //                 ->delete();

    //             foreach ($toAdd as $serviceId) {
    //                 UserService::create([
    //                     'user_id' => $id,
    //                     'service_id' => $serviceId,
    //                 ]);
    //             }
    //         }

    //         if ($request->language_names) {
    //             $newlanguages = $request->input('language_names', []);
    //             $existinglanguages = UserLanguage::where('user_id', $id)
    //                 ->pluck('language_id')
    //                 ->toArray();

    //             $toDelete = array_diff($existinglanguages, $newlanguages);
    //             $toAdd = array_diff($newlanguages, $existinglanguages);

    //             UserLanguage::where('user_id', $id)
    //                 ->whereIn('language_id', $toDelete)
    //                 ->delete();

    //             foreach ($toAdd as $languageId) {
    //                 UserLanguage::create([
    //                     'user_id' => $id,
    //                     'language_id' => $languageId,
    //                 ]);
    //             }
    //         }

    //         if ($request->coach_subtype) {
    //             $newCoach_sub_type = $request->input('coach_subtype', []);
    //             $existingCoach_sub_type = CoachSubTypeUser::where('user_id', $id)
    //                         ->pluck('coach_subtype_id')
    //                         ->toArray();

    //             $toDelete = array_diff($existingCoach_sub_type, $newCoach_sub_type);
    //             $toAdd = array_diff($newCoach_sub_type, $existingCoach_sub_type);

    //             CoachSubTypeUser::where('user_id', $id)
    //                 ->whereIn('coach_subtype_id', $toDelete)
    //                 ->delete();


    //             foreach ($toAdd as $CoachSubTypeId) {
    //                 CoachSubTypeUser::create([
    //                     'user_id' => $id,
    //                     'coach_subtype_id' => $CoachSubTypeId,
    //                 ]);
    //             }
    //         }
    //     }

    //     $data = [
    //         'id'                   => $id,
    //         'first_name'           => $request->first_name,
    //         'last_name'            => $request->last_name,
    //         'email'                => $request->email,
    //         'country_id'           => $request->country_id,
    //         'city_id'              => $request->city_id,
    //         'gender'               => $request->gender,
    //         'professional_title'   => $request->professional_title,
    //         'company_name'         => $request->company_name,
    //         'exp_and_achievement'  => $request->exp_and_achievement,
    //         'detailed_bio'         => $request->detailed_bio,
    //         'is_corporate'         => $request->is_corporate ?? 0,
    //         'experience'           => $request->experience,
    //         'coaching_category'    => $request->coaching_category,
    //         'delivery_mode'        => $request->delivery_mode,
    //         'average_charge_hour'  => $request->average_charge_hour,
    //         'price_range'          => $request->price_range,
    //         'age_group'            => $request->age_group,
    //         'free_trial_session'   => $request->free_trial_session,
    //         'is_pro_bono'          => $request->is_pro_bono,
    //         'linkdin_link'         => $request->linkdin_link ?? '',
    //         'website_link'         => $request->website_link ?? '',
    //         'youtube_link'         => $request->youtube_link,
    //         'podcast_link'         => $request->podcast_link,
    //         'blog_article'         => $request->blog_article,
    //         'service_keyword'      => $request->service_keyword ?? [],
    //         'language'             => $request->language ?? [],
    //         'coach_subtype'        => $request->coach_subtype ?? [],
    //     ];



    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Profile updated successfully',
    //         'data' => $data,
    //     ]);
    // }

     public function updateProfile(Request $request)
    {

        $coach = Auth::user(); 


        $id = $coach->id;
        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or inactive.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', 0);
                })->ignore($id), 
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
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
            ->first();

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or inactive.',
            ], 404);
        }


        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
            if ($coach->profile_image && file_exists(public_path('/uploads/profile_image/' . $coach->profile_image))) {
                unlink(public_path('/uploads/profile_image/' . $coach->profile_image));
            }

            $image->move(public_path('/uploads/profile_image'), $imageName);
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
        $coach->exp_and_achievement = $request->exp_and_achievement;
        $coach->detailed_bio = $request->detailed_bio;
        $coach->is_corporate = $request->is_corporate ?? 0; 


        $coach->save();
        if (!$coach->userProfessional) {
            $coach->userProfessional()->create([
                'experience' => $request->experience ?? null,
                'coaching_category' => $request->coaching_category ?? null,
                'delivery_mode' => $request->delivery_mode ?? null,
                'price' => $request->price ?? null,
                'price_range' => $request->price_range ?? null,
                'age_group' => $request->age_group ?? null,
                'coach_type' => $request->coach_type ?? null,
                'free_trial_session' => $request->free_trial_session ?? null,
                'is_pro_bono' => $request->is_pro_bono ?? null,
                'linkdin_link' => $request->linkdin_link ?? null,
                'website_link' => $request->website_link ?? null,
                'youtube_link' => $request->youtube_link ?? null,
                'podcast_link' => $request->podcast_link ?? null,
                'blog_article' => $request->blog_article ?? null,
                'communication_channel' => $request->communication_channel ?? null,
                'budget_range' => $request->budget_range ?? null,
            ]);
        } else {
            $up = $coach->userProfessional;
            $up->experience = $request->experience ?? null;
            $up->coaching_category = $request->coaching_category ?? null;
            $up->delivery_mode = $request->delivery_mode ?? null;
            $up->price = $request->price ?? null;
            $up->price_range = $request->price_range ?? null;
            $up->age_group = $request->age_group ?? null;
            $up->coach_type = $request->coach_type ?? null;
            $up->free_trial_session = $request->free_trial_session ?? null;
            $up->is_pro_bono = $request->is_pro_bono ?? null;
            $up->linkdin_link = $request->linkdin_link ?? null;
            $up->website_link = $request->website_link ?? null;
            $up->youtube_link = $request->youtube_link ?? null;
            $up->podcast_link = $request->podcast_link ?? null;
            $up->blog_article = $request->blog_article ?? null;
            $up->save();
        }



            if ($request->hasFile('video_link')) {
                $image = $request->file('video_link');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $oldVideo = $coach->userProfessional->video_link;
                if ($oldVideo && file_exists(public_path('/uploads/coach_video/' . $oldVideo))) {
                    unlink(public_path('/uploads/coach_video/' . $oldVideo));
                }
                $image->move(public_path('/uploads/coach_video'), $fileName);
                $coach->userProfessional->video_link = $fileName;
            }



            if ($request->hasFile('upload_credentials')) {
                $oldDocs = UserDocument::where('user_id', $id)->where('document_type', 1)->get();
                foreach ($oldDocs as $doc) {
                    $filePath = public_path('/uploads/documents/' . $doc->document_file);
                    if (file_exists($filePath)) {
                        unlink($filePath); 
                    }
                    $doc->delete(); 
                }

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


            if ($request->service_keyword) {

                $newServiceIds = $request->input('service_keyword', []);
                $existingServiceIds = UserService::where('user_id', $id)
                    ->pluck('service_id')
                    ->toArray();

                $toDelete = array_diff($existingServiceIds, $newServiceIds);
                $toAdd = array_diff($newServiceIds, $existingServiceIds);

                UserService::where('user_id', $id)
                    ->whereIn('service_id', $toDelete)
                    ->delete();

                foreach ($toAdd as $serviceId) {
                    UserService::create([
                        'user_id' => $id,
                        'service_id' => $serviceId,
                    ]);
                }
            }

            if ($request->language_names) {
                $newlanguages = $request->input('language_names', []);
                $existinglanguages = UserLanguage::where('user_id', $id)
                    ->pluck('language_id')
                    ->toArray();

                $toDelete = array_diff($existinglanguages, $newlanguages);
                $toAdd = array_diff($newlanguages, $existinglanguages);

                UserLanguage::where('user_id', $id)
                    ->whereIn('language_id', $toDelete)
                    ->delete();

                foreach ($toAdd as $languageId) {
                    UserLanguage::create([
                        'user_id' => $id,
                        'language_id' => $languageId,
                    ]);
                }
            }

            if ($request->coach_subtype) {
                $newCoach_sub_type = $request->input('coach_subtype', []);
                $existingCoach_sub_type = CoachSubTypeUser::where('user_id', $id)
                            ->pluck('coach_subtype_id')
                            ->toArray();

                $toDelete = array_diff($existingCoach_sub_type, $newCoach_sub_type);
                $toAdd = array_diff($newCoach_sub_type, $existingCoach_sub_type);

                CoachSubTypeUser::where('user_id', $id)
                    ->whereIn('coach_subtype_id', $toDelete)
                    ->delete();


                foreach ($toAdd as $CoachSubTypeId) {
                    CoachSubTypeUser::create([
                        'user_id' => $id,
                        'coach_subtype_id' => $CoachSubTypeId,
                    ]);
                }
            }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $coach,
        ]);
    }

     public function change_password(Request $request)
    {

            try {
                $validator = Validator::make($request->all(), [
                    'current_password' => 'required|string',
                    'new_password' => 'required|string|min:6|confirmed',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $user = Auth::user();

                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json(['error' => 'Current password is incorrect.'], 400);
                }

                if ($request->current_password === $request->new_password) {
                    return response()->json(['error' => 'New password cannot be the same as the current password.'], 400);
                }

                $user->password = Hash::make($request->new_password);
                $user->save();

                return response()->json(['message' => 'Password updated successfully.'], 200);
             } catch (Exception $e) {
                \Log::error('Password change error: '.$e->getMessage());

                return response()->json([
                    'error' => 'Something went wrong. Please try again later.'
                ], 500);
            }
    }

    public function setting(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'new_coach_match_alert' => 'nullable|boolean',
            'message_notifications' => 'nullable|boolean',
            'booking_reminders' => 'nullable|boolean',
            'coaching_request_status' => 'nullable|boolean',
            'platform_announcements' => 'nullable|boolean',
            'blog_article_recommendations' => 'nullable|boolean',
            'billing_updates' => 'nullable|boolean',
            'communication_preference' => 'nullable|string',
            'profile_visibility' => 'nullable|string',
            'allow_ai_matching' => 'nullable|boolean',
        ]);

        $setting = Setting::firstOrNew(['user_id' => $user->id]);

        foreach ($validated as $key => $value) {
            if (!is_null($value)) {
                $setting->$key = $value;
            }
        }

        $setting->save();

        return response()->json([
            'message' => 'Settings updated successfully.',
            'settings' => $setting,
        ]);
    }


        public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Account deleted successfully.']);
    }



}