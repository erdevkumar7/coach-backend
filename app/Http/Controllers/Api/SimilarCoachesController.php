<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\User;
use App\Models\CoachingRequest;
use App\Models\BookingPackages;
use App\Models\UserServicePackage;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class SimilarCoachesController extends Controller
{
    public function SimilarCoaches(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'coach_id'  => 'required',
        ]);


        $coachId = $request->input('coach_id');

        // $user_detail = Professional::where('user_id', $coachId)->first();
        // if (!$user_detail) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'User professional details not found',
        //     ], 404);
        // }

        // $coach_type = $user_detail->coach_type;

        // $similarCoaches = Professional::with('user')
        //     ->where('coach_type', $coach_type)
        //     ->where('user_id', '!=', $coachId)
        //     ->limit(5)
        //     ->get();

        $currentCoach = User::with('userProfessional')->find($coachId);
        $coachTypeId = $currentCoach->userProfessional->coach_type ?? null;

        $similarCoaches = User::with(['services.servicename'])
            ->where('id', '!=', $currentCoach->id)
            ->where('user_status', 1)
            ->where('user_type', 3)
            ->whereHas('userProfessional', function ($q) use ($coachTypeId) {
                $q->where('coach_type', $coachTypeId);
            })
            ->limit(5)
            ->get();


        $similarCoaches = $similarCoaches->map(function ($coach) {
            return [
                'id' => $coach->id,
                'first_name' => $coach->first_name,
                'last_name' => $coach->last_name,
                'professional_title' => $coach->professional_title,
                'company_name' => $coach->company_name,
                'profile_image' => $coach->profile_image
                    ? url('public/uploads/profile_image/' . $coach->profile_image)
                    : '',
                'service_names' => $coach->services->pluck('servicename.service')->filter()->values(),
            ];
        });


        if ($similarCoaches->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No similar coaches found',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Similar coaches data list',
            'data' => $similarCoaches
        ]);
    }
    // public function getPendingCoaching(Request $request)
    // {
    //     $user = Auth::user(); // Authenticated user

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not authenticated.',
    //         ], 403);
    //     }

    //     $id = $user->id;
    //     //    echo $id;die;
    //     $perPage = $request->per_page ?? 6;
    //     $page = $request->input('page', 1);

    //     // Determine relationship & filter based on user type
    //     if ($user->user_type == 2) { // Coach
    //         $relation = 'coach';
    //         $filterColumn = 'user_id';
    //     } else { // Normal User
    //         $relation = 'user';
    //         $filterColumn = 'coach_id';
    //     }

    //     $coachingRequests = CoachingRequest::with([
    //         $relation . '.country',
    //         $relation . '.userProfessional.coachType',
    //         $relation . '.reviews',
    //         $relation . '.languages.languagename',
    //         'coachingCategory',
    //         'coachingSubCategory',
    //         'delivery_mode',
    //         'communicationChannel',
    //         'ageGroup',
    //         'budgetRange',
    //         'coachExperience',
    //         'dateUrgency',
    //         'lokingFor',
    //     ])->where($filterColumn, $id)
    //         ->orderBy('coaching_request.id', 'desc')
    //         ->paginate($perPage, ['*'], 'page', $page);

    //     // print_r($coachingRequests);die;

    //     $results = $coachingRequests->getCollection()->map(function ($req) use ($relation) {
    //         $show_relation = $relation;
    //         $reviews = $req->$show_relation->reviews ?? collect();
    //         $avgRating = $reviews->avg('rating');
    //         return [
    //             'id'         => $req->$show_relation->id ?? null,
    //             'request_id' => $req->id ?? null,
    //             'coaching_request_goal' => $req->coaching_goal ?? null,
    //             'first_name' => $req->$show_relation->first_name ?? null,
    //             'last_name'  => $req->$show_relation->last_name ?? null,
    //             'coach_category'  => $req->coachingCategory->type_name ?? null,
    //             'coach_type'  => $req->lokingFor->type_name ?? null,
    //             'coach_subtype'  => $req->coachingSubCategory->subtype_name ?? null,
    //             'delivery_mode'  => $req->delivery_mode->mode_name ?? null,
    //             'user_type'  => $req->$show_relation->user_type ?? null,
    //             'languages' => $req->$show_relation->languages->pluck('languagename.language')->toArray(),
    //             // 'coaching_category'    => $req->coach->userProfessional->coachType->type_name ?? null,
    //             'prefered_communication_channel'  => $req->communicationChannel->category_name ?? null,
    //             'target_age_group'  => $req->ageGroup->group_name ?? null,
    //             'budget_range'  => $req->budgetRange->budget_range ?? null,
    //             'experience_level'  => $req->coachExperience->experience_level ?? null,
    //             // 'prefered_schedule'  => $req->preferred_schedule ?? null,
    //             'gender_prefernece'  => $req->$show_relation->gender ?? null,
    //             'certified_coach'  => $req->$show_relation->is_verified ?? null,
    //             'prefered_urgency_date'  => $req->dateUrgency->prefer_start_date ?? null,
    //             'company_name'    => $req->$show_relation->company_name ?? null,
    //             'review_coach'    => $avgRating ?? null,
    //             'profile_image' => $req->$show_relation->profile_image
    //                 ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
    //                 : '',
    //             'country'    => $req->$show_relation->country->country_name ?? null,
    //             'created_at'         => $req->created_at ?? null,
    //             'updated_at'         => $req->updated_at ?? null,
    //         ];
    //     });
    //     //  echo 'test';die;
    //     return response()->json([
    //         'success' => true,
    //         'request_count' => $coachingRequests->total(),
    //         'data' => $results,
    //         'pagination' => [
    //             'total'        => $coachingRequests->total(),
    //             'per_page'     => $coachingRequests->perPage(),
    //             'current_page' => $coachingRequests->currentPage(),
    //             'last_page'    => $coachingRequests->lastPage(),
    //             'from'         => $coachingRequests->firstItem(),
    //             'to'           => $coachingRequests->lastItem(),
    //         ],
    //     ]);
    // }

        public function getPendingCoaching(Request $request)
    {
        $user = Auth::user(); 

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        $id = $user->id;
        $perPage = $request->per_page ?? 6;
        $page = $request->input('page', 1);

        if ($user->user_type == 2) { 
            $relation = 'coach';
            $filterColumn = 'user_id';
        } else { 
            $relation = 'user';
            $filterColumn = 'coach_id';
        }

        $coachingRequests = CoachingRequest::with([
            $relation . '.country',
            $relation . '.userProfessional.coachType',
            $relation . '.reviews',
            $relation . '.languages.languagename',
            'coachingCategory',
            'coachingSubCategory',
            'delivery_mode',
            'communicationChannel',
            'ageGroup',
            'budgetRange',
            'coachExperience',
            'dateUrgency',
            'lokingFor',
        ])->where($filterColumn, $id)
            ->orderBy('coaching_request.id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // print_r($coachingRequests);die;

        $results = $coachingRequests->getCollection()->map(function ($req) use ($relation) {
            $show_relation = $relation;
            $reviews = $req->$show_relation->reviews ?? collect();
            $avgRating = $reviews->avg('rating');
            return [
                'id'         => $req->$show_relation->id ?? null,
                'request_id' => $req->id ?? null,
                'coaching_request_goal' => $req->coaching_goal ?? null,
                'first_name' => $req->$show_relation->first_name ?? null,
                'last_name'  => $req->$show_relation->last_name ?? null,
                'coach_category'  => $req->coachingCategory->type_name ?? null,
                'coach_type'  => $req->lokingFor->type_name ?? null,
                'coach_subtype'  => $req->coachingSubCategory->subtype_name ?? null,
                'delivery_mode'  => $req->delivery_mode->mode_name ?? null,
                'user_type'  => $req->$show_relation->user_type ?? null,
                'languages' => $req->languages->pluck('language')->filter()->values()->toArray(),
                'prefered_communication_channel'  => $req->communicationChannel->category_name ?? null,
                'target_age_group'  => $req->ageGroup->group_name ?? null,
                'budget_range'  => $req->budgetRange->budget_range ?? null,
                'experience_level'  => $req->coachExperience->experience_level ?? null,
                'gender_prefernece'  => $req->$show_relation->gender ?? null,
                'certified_coach'  => $req->$show_relation->is_verified ?? null,
                'prefered_urgency_date'  => $req->dateUrgency->prefer_start_date ?? null,
                'company_name'    => $req->$show_relation->company_name ?? null,
                'review_coach'    => $avgRating ?? null,
                'profile_image' => $req->$show_relation->profile_image
                    ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                    : '',
                'country'    => $req->$show_relation->country->country_name ?? null,
                'created_at'         => $req->created_at ?? null,
                'updated_at'         => $req->updated_at ?? null,
            ];
        });
        return response()->json([
            'success' => true,
            'request_count' => $coachingRequests->total(),
            'data' => $results,
            'pagination' => [
                'total'        => $coachingRequests->total(),
                'per_page'     => $coachingRequests->perPage(),
                'current_page' => $coachingRequests->currentPage(),
                'last_page'    => $coachingRequests->lastPage(),
                'from'         => $coachingRequests->firstItem(),
                'to'           => $coachingRequests->lastItem(),
            ],
        ]);
    }

    public function getCoachingPackages(Request $request)
    {
        $user = Auth::user(); // Authenticated user

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        $id = $user->id;
        // echo $id;die;
        $perPage = $request->per_page ?? 6;
        $page = $request->input('page', 1);

        // Determine relationship & filter based on user type
        if ($user->user_type == 2) { // Coach
            $relation = 'coach';
            $filterColumn = 'user_id';
        } else { // Normal User
            $relation = 'user';
            $filterColumn = 'coach_id';
        }

        $now = Carbon::now();


        $bookPackages = BookingPackages::with([
            $relation . '.country',
            $relation . '.userProfessional.coachType',
            'coachPackage',
        ])
            ->where($filterColumn, $id)
            ->where(function ($q) use ($now) {
                // Keep if upcoming OR currently in-progress
                $q->whereRaw("STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s') > ?", [$now])
                    ->orWhereRaw("? BETWEEN STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s')
                              AND STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s')", [$now]);
            })
            ->orderBy('booking_packages.id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);


        $results = $bookPackages->getCollection()->map(function ($req) use ($relation, $now) {
            $show_relation = $relation;


            $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);
            $endDateTime   = Carbon::parse($req->session_date_end . ' ' . $req->slot_time_end);
            $endDate       = Carbon::parse($req->session_date_end)->endOfDay();


            $status = null;
            if ($now->between($startDateTime, $endDateTime)) {
                $status = 'in-progress';
            } elseif ($now->lt($startDateTime)) {
                $status = 'confirmed';
            }


        // ✅ Calculate available session slots
        $package = $req->coachPackage;
        $sessionLeft = 0;

        if ($package) {
            // Count total days
            $start = Carbon::parse($package->booking_availability_start);
            $end = Carbon::parse($package->booking_availability_end);
            $dayCount = $start->diffInDays($end) + 1;

            // Total possible slots
            $totalSlotsOfPackage = $dayCount * $package->booking_slots;

            // Count booked sessions (exclude cancelled status = 3)
            $bookedPackages = BookingPackages::where('package_id', $package->id)
                ->where('status', '!=', 3)
                ->count();

            // Calculate remaining sessions
            $sessionLeft = max(0, $totalSlotsOfPackage - $bookedPackages);
        }


            return [
                'id'                => $req->$show_relation->id ?? null,
                'booking_id'        => $req->id ?? null,
                'first_name'        => $req->$show_relation->first_name ?? null,
                'last_name'         => $req->$show_relation->last_name ?? null,
                'user_type'         => $req->$show_relation->user_type ?? null,
                'display_name'      => $req->$show_relation->display_name ?? null,
                'package_title'     => $req->coachPackage->title ?? null,
                'package_id'        => $req->coachPackage->id ?? null,
                'package_coach_id'     => $req->coachPackage->coach_id ?? null,
                'profile_image'     => $req->$show_relation->profile_image
                    ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                    : '',
                'session_date_start' => $req->session_date_start ?? null,
                'slot_time_start'    => $req->slot_time_start ?? null,
                'session_date_end'   => $req->session_date_end ?? null,
                'slot_time_end'      => $req->slot_time_end ?? null,
                'country'            => $req->$show_relation->country->country_name ?? null,
                'status'             => $status ?? null,
                'session_left'       => $sessionLeft ?? 0,
                // 'created_at'         => $req->created_at ?? null,
                // 'updated_at'         => $req->updated_at ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'request_count' => $results->count(),
            'data' => $results->values(),
            'pagination' => [
                'total'        => $bookPackages->total(),
                'per_page'     => $bookPackages->perPage(),
                'current_page' => $bookPackages->currentPage(),
                'last_page'    => $bookPackages->lastPage(),
                'from'         => $bookPackages->firstItem(),
                'to'           => $bookPackages->lastItem(),
            ],
        ]);
    }



    // public function getPackagesCompleted(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not authenticated.',
    //         ], 403);
    //     }

    //     $id = $user->id;
    //     $perPage = $request->per_page ?? 6;
    //     $page = $request->input('page', 1);

    //     if ($user->user_type == 2) {
    //         $relation = 'coach';
    //         $filterColumn = 'user_id';
    //     } else {
    //         $relation = 'user';
    //         $filterColumn = 'coach_id';
    //     }

    //     $now = Carbon::now();

    //     $bookPackages = BookingPackages::with([
    //         $relation . '.country',
    //         $relation . '.userProfessional.coachType',
    //         'coachPackage',
    //     ])
    //         ->where($filterColumn, $id)
    //         ->whereRaw("
    //             STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s') < ?
    //         ", [$now])
    //         ->orderBy('booking_packages.id', 'desc')
    //         ->paginate($perPage, ['*'], 'page', $page);

    //     $results = $bookPackages->getCollection()->map(function ($req) use ($relation) {
    //         return [
    //             'id'                => $req->$relation->id ?? null,
    //             'booking_id'        => $req->id ?? null,
    //             'first_name'        => $req->$relation->first_name ?? null,
    //             'last_name'         => $req->$relation->last_name ?? null,
    //             'user_type'         => $req->$relation->user_type ?? null,
    //             'display_name'      => $req->$relation->display_name ?? null,
    //             'package_title'     => $req->coachPackage->title ?? null,
    //             'profile_image'     => $req->$relation->profile_image
    //                 ? url('public/uploads/profile_image/' . $req->$relation->profile_image)
    //                 : '',
    //             'session_date_start' => $req->session_date_start ?? null,
    //             'slot_time_start'    => $req->slot_time_start ?? null,
    //             'session_date_end'   => $req->session_date_end ?? null,
    //             'slot_time_end'      => $req->slot_time_end ?? null,
    //             'country'            => $req->$relation->country->country_name ?? null,
    //             'status'             => 'completed',
    //             'session_left'       => 0,
    //         ];
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'request_count' => $bookPackages->total(),
    //         'data' => $results->values(),
    //         'pagination' => [
    //             'total'        => $bookPackages->total(),
    //             'per_page'     => $bookPackages->perPage(),
    //             'current_page' => $bookPackages->currentPage(),
    //             'last_page'    => $bookPackages->lastPage(),
    //             'from'         => $bookPackages->firstItem(),
    //             'to'           => $bookPackages->lastItem(),
    //         ],
    //     ]);
    // }

    // public function getPackagesCompleted(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not authenticated.',
    //         ], 403);
    //     }

    //     $perPage = $request->input('per_page', 6);
    //     $page = $request->input('page', 1);


    //     $totalItems = BookingPackages::where('user_id', $user->id)
    //         ->where('status', 2)
    //         ->count();

    //     $lastPage = max(ceil($totalItems / $perPage), 1);
    //     if ($page > $lastPage) {
    //         $page = 1;
    //     }

    //     $bookPackages = BookingPackages::with([
    //             'coach.country',
    //             'coach.userProfessional.coachType',
    //             'coachPackage','coach.coachreview',
    //         ])
    //         ->where('user_id', $user->id)
    //         ->where('status', 2)
    //         ->paginate($perPage, ['*'], 'page', $page);

    //     return response()->json([
    //         'success' => true,
    //         'request_count' => $bookPackages->total(),
    //         // 'data' => $bookPackages->items(),
    //         'data' => $bookPackages->getCollection()->transform(function ($item) {
    //         return [
    //             'booking_id'         => $item->id,
    //             'first_name'         => $item->coach->first_name ?? '',
    //             'last_name'          => $item->coach->last_name ?? '',
    //             'user_type'          => $item->coach->user_type ?? '',
    //             'display_name'       => $item->coach->display_name ?? '',
    //             'package_title'      => $item->coachPackage->title ?? '',
    //             'profile_image'      => !empty($item->coach->profile_image)
    //                                     ? url('public/uploads/profile_image/' . $item->coach->profile_image)
    //                                     : '',
    //             'session_date_start' => $item->session_date_start,
    //             'slot_time_start'    => $item->slot_time_start,
    //             'session_date_end'   => $item->session_date_end,
    //             'slot_time_end'      => $item->slot_time_end,
    //             'country'            => $item->coach->country->country_name ?? '',
    //             'review'             => $item->coachreview ? [
    //                 'rating'      => $item->coachreview->rating,
    //                 'review_text' => $item->coachreview->review_text,
    //             ] : null
    //         ];
    //     }),
    //         'pagination' => [
    //             'total'        => $bookPackages->total(),
    //             'per_page'     => $bookPackages->perPage(),
    //             'current_page' => $bookPackages->currentPage(),
    //             'last_page'    => $bookPackages->lastPage(),
    //             'from'         => $bookPackages->firstItem(),
    //             'to'           => $bookPackages->lastItem(),
    //         ],
    //     ]);
    // }

    public function getPackagesCompleted(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        $user_id = $user->id;

        // Determine relation and filter column based on user type
        if ($user->user_type == 2) {
            $relation = 'coach';
            $filterColumn = 'user_id';
        } else {
            $relation = 'user';
            $filterColumn = 'coach_id';
        }

        $perPage = $request->input('per_page', 6);
        $page = $request->input('page', 1);

        // ✅ Use variable correctly (no quotes)
        $totalCompletedItems = BookingPackages::where($filterColumn, $user_id)
            ->where('status', '!=', 3)
            ->whereRaw("CONCAT(session_date_end, ' ', slot_time_end) < ?", [Carbon::now()])
            ->count();

        $lastPage = max(ceil($totalCompletedItems / $perPage), 1);
        if ($page > $lastPage) {
            $page = 1;
        }

        // ✅ Use variable correctly in relationships and filters
        $bookPackages = BookingPackages::with([
            "{$relation}.country",
            "{$relation}.userProfessional",
            'coachPackage',
            'reviewByPackageId'
        ])
            ->where($filterColumn, $user_id)
            ->where('status', '!=', 3)
            ->whereRaw("CONCAT(session_date_end, ' ', slot_time_end) < ?", [Carbon::now()])
            ->orderByDesc('session_date_end')
            ->paginate($perPage, ['*'], 'page', $page);

        // ✅ Transform data properly
        $data = $bookPackages->getCollection()->transform(function ($item) use ($filterColumn, $relation) {
            return [
                'booking_id'         => $item->id,
                'package_booked_user_id'         => $item->user_id,
                $filterColumn        => $item->$filterColumn,
                'first_name'         => $item->$relation->first_name ?? '',
                'last_name'          => $item->$relation->last_name ?? '',
                'user_type'          => $item->$relation->user_type ?? '',
                'display_name'       => $item->$relation->display_name ?? '',
                //'id'                => $item->$relation->id ?? null,
                'package_id'         => $item->coachPackage->id ?? '',
                'package_coach_id'      => $item->coachPackage->coach_id ?? '',
                'package_title'      => $item->coachPackage->title ?? '',
                'profile_image'      => !empty($item->$relation->profile_image)
                    ? url('public/uploads/profile_image/' . $item->$relation->profile_image)
                    : '',
                'session_date_start' => $item->session_date_start,
                'slot_time_start'    => $item->slot_time_start,
                'session_date_end'   => $item->session_date_end,
                'slot_time_end'      => $item->slot_time_end,
                'country'            => $item->$relation->country->country_name ?? '',
                'review'             => $item->reviewByPackageId ? [
                    'rating'      => $item->reviewByPackageId->rating,
                    'review_text' => $item->reviewByPackageId->review_text,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'request_count' => $bookPackages->total(),
            'data' => $data,
            'pagination' => [
                'total'        => $bookPackages->total(),
                'per_page'     => $bookPackages->perPage(),
                'current_page' => $bookPackages->currentPage(),
                'last_page'    => $bookPackages->lastPage(),
                'from'         => $bookPackages->firstItem(),
                'to'           => $bookPackages->lastItem(),
            ],
        ]);
    }


    public function getPackagesCanceled(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        $user_id = $user->id;

        // Determine relation and filter column based on user type
        if ($user->user_type == 2) {
            $relation = 'coach';
            $filterColumn = 'user_id';
        } else {
            $relation = 'user';
            $filterColumn = 'coach_id';
        }

        $perPage = $request->input('per_page', 6);
        $page = $request->input('page', 1);

        // ✅ Use variable correctly (no quotes)
        $totalCanceledItems = BookingPackages::where($filterColumn, $user_id)
            ->where('status', '=', 3)
            ->count();

        $lastPage = max(ceil($totalCanceledItems / $perPage), 1);
        if ($page > $lastPage) {
            $page = 1;
        }

        // ✅ Use variable correctly in relationships and filters
        $bookPackages = BookingPackages::with([
            "{$relation}.country",
            "{$relation}.userProfessional",
            'coachPackage',
            'reviewByPackageId'
        ])
            ->where($filterColumn, $user_id)
            ->where('status', '=', 3)
            ->orderByDesc('session_date_end')
            ->paginate($perPage, ['*'], 'page', $page);





        // ✅ Transform data properly
        $data = $bookPackages->getCollection()->transform(function ($item) use ($filterColumn, $relation) {


                // ✅ Calculate available session slots
        $package = $item->coachPackage;
        $sessionLeft = 0;

        if ($package) {
            // Count total days
            $start = Carbon::parse($package->booking_availability_start);
            $end = Carbon::parse($package->booking_availability_end);
            $dayCount = $start->diffInDays($end) + 1;

            // Total possible slots
            $totalSlotsOfPackage = $dayCount * $package->booking_slots;

            // Count booked sessions (exclude cancelled status = 3)
            $bookedPackages = BookingPackages::where('package_id', $package->id)
                ->where('status', '!=', 3)
                ->count();

            // Calculate remaining sessions
            $sessionLeft = max(0, $totalSlotsOfPackage - $bookedPackages);
        }


            return [
                'booking_id'         => $item->id,
                'package_booked_user_id' => $item->user_id,
                $filterColumn        => $item->$filterColumn,
                'first_name'         => $item->$relation->first_name ?? '',
                'last_name'          => $item->$relation->last_name ?? '',
                'user_type'          => $item->$relation->user_type ?? '',
                'display_name'       => $item->$relation->display_name ?? '',
                //'id'                => $item->$relation->id ?? null,
                'package_id'      => $item->coachPackage->id ?? '',
                'package_coach_id'      => $item->coachPackage->coach_id ?? '',
                'package_title'      => $item->coachPackage->title ?? '',
                'profile_image'      => !empty($item->$relation->profile_image)
                    ? url('public/uploads/profile_image/' . $item->$relation->profile_image)
                    : '',
                'session_date_start' => $item->session_date_start,
                'slot_time_start'    => $item->slot_time_start,
                'session_date_end'   => $item->session_date_end,
                'slot_time_end'      => $item->slot_time_end,
                'country'            => $item->$relation->country->country_name ?? '',
                'session_left' => $sessionLeft ?? 0,
                'review'             => $item->reviewByPackageId ? [
                    'rating'      => $item->reviewByPackageId->rating,
                    'review_text' => $item->reviewByPackageId->review_text,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'request_count' => $bookPackages->total(),
            'data' => $data,
            'pagination' => [
                'total'        => $bookPackages->total(),
                'per_page'     => $bookPackages->perPage(),
                'current_page' => $bookPackages->currentPage(),
                'last_page'    => $bookPackages->lastPage(),
                'from'         => $bookPackages->firstItem(),
                'to'           => $bookPackages->lastItem(),
            ],
        ]);
    }

    public function getRecentCoachingActivities(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.',
            ], 403);
        }

        $user_id = $user->id;

        // Determine relation and filter column based on user type
        if ($user->user_type == 2) {
            $relation = 'coach';
            $filterColumn = 'user_id';
        } else {
            $relation = 'user';
            $filterColumn = 'coach_id';
        }

        // ✅ Show activities completed in the last 15 days
        $startDate = Carbon::now()->subDays(10);
        $endDate = Carbon::now();

        $bookPackages = BookingPackages::with([
            "{$relation}.country",
            "{$relation}.userProfessional",
            'coachPackage',
            'reviewByPackageId'
        ])
            ->where($filterColumn, $user_id)
            ->where('status', '!=', 3)
            ->whereBetween(DB::raw("CONCAT(session_date_end, ' ', slot_time_end)"), [$startDate, $endDate])
            ->orderByDesc('session_date_end')
            ->limit(2)
            ->get();

        // ✅ Transform data properly
        $data['complete'] = $bookPackages->map(function ($item) use ($filterColumn, $relation) {
            return [
                'booking_id'         => $item->id,
                $filterColumn        => $item->$filterColumn,
                'first_name'         => $item->$relation->first_name ?? '',
                'last_name'          => $item->$relation->last_name ?? '',
                'user_type'          => $item->$relation->user_type ?? '',
                'display_name'       => $item->$relation->display_name ?? '',
                'package_id'         => $item->coachPackage->id ?? '',
                'package_title'      => $item->coachPackage->title ?? '',
                'profile_image'      => !empty($item->$relation->profile_image)
                    ? url('public/uploads/profile_image/' . $item->$relation->profile_image)
                    : '',
                'session_date_start' => $item->session_date_start,
                'slot_time_start'    => $item->slot_time_start,
                'session_date_end'   => $item->session_date_end,
                'slot_time_end'      => $item->slot_time_end,
                'country'            => $item->$relation->country->country_name ?? '',
                'review'             => $item->reviewByPackageId ? [
                    'rating'      => $item->reviewByPackageId->rating,
                    'review_text' => $item->reviewByPackageId->review_text,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Recent coaching activities from last 15 days.',
            'count'   => $bookPackages->count(),
            'data'    => $data,
        ]);
    }
}
