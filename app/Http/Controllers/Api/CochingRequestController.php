<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingPackages;
use App\Models\CoachingRequest;
use App\Models\MasterCountry;
use App\Models\Message;
use App\Models\Professional;
use App\Models\User;
use App\Models\UserLanguage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CochingRequestController extends Controller
{
    public function cochingRequestSend(Request $request)
    {
        // print_r($request->all());die;
        // echo "test";die;
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $user_type = 3; // 3 user type is coach
        $coach_type = $request->coach_type; // category
        $coach_subtype = $request->coach_subtype; // couch sub type category
        $delivery_mode = $request->preferred_mode_of_delivery; //
        $country = $request->location; // country
        $languageIds = $request->language_preference;            //[3, 4, 8];
        $communication_channel = $request->preferred_communication_channel;
        $learner_age_group = $request->learner_age_group; // age group
        $preferred_coaching = $request->preferred_teaching_style; // Coaching category fld
        $only_certified_coach = $request->only_certified_coach; // verified coach
        $coach_gender = $request->coach_gender; // male female, othor
        $coach_experience_level = $request->coach_experience_level;
        $budget_range = $request->budget_range;
        $preferred_start_date_urgency = $request->preferred_start_date_urgency;
        $share_with_coaches = $request->share_with_coaches;
        $preferred_schedule = $request->preferred_schedule;


        $validator = Validator::make($request->all(), [
            'coach_type'      => 'required|integer',
            'coach_subtype'       => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }


        $usersshow = User::with([
            'services',
            'languages',
            'userServicePackages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'coachsubtypeuser',
            'country',
        ])
            ->where('users.user_type', $user_type)

            // user type user or coach
            ->whereHas('userProfessional', function ($query) use ($coach_type) {
                $query->where('coach_type', $coach_type);
            })
            // Coach sub type user filter
            ->whereHas('coachsubtypeuser', function ($query) use ($coach_subtype) {
                if (!empty($coach_subtype)) {
                    $query->where('coach_subtype_id', $coach_subtype);
                }
            })



            // Delivary mode filter
            ->when(!empty($delivery_mode), function ($query) use ($delivery_mode) {
                $query->whereHas('userProfessional', function ($q) use ($delivery_mode) {
                    $q->where('delivery_mode', $delivery_mode);
                });
            })


            // Location or Country Filter
            ->when(!empty($country), function ($query) use ($country) {
                $query->where('users.country_id', $country);
            })


            // Language Preference Filter
            ->when(!empty($languageIds), function ($query) use ($languageIds) {
                $query->whereHas('languages', function ($q) use ($languageIds) {
                    $q->whereIn('language_id', $languageIds);
                });
            })

            // Preferred Communication Channel Filter
            ->when(!empty($communication_channel), function ($query) use ($communication_channel) {
                $query->whereHas('userServicePackages', function ($q) use ($communication_channel) {
                    $q->where('communication_channel', $communication_channel);
                });
            })

            // Age Group filter
            ->when(!empty($learner_age_group), function ($query) use ($learner_age_group) {
                $query->whereHas('userProfessional', function ($q) use ($learner_age_group) {
                    $q->where('age_group', $learner_age_group);
                });
            })

            // Preferred Coaching/Teaching Style Filter
            ->when(!empty($preferred_coaching), function ($query) use ($preferred_coaching) {
                $query->whereHas('userProfessional', function ($q) use ($preferred_coaching) {
                    $q->where('coaching_category', $preferred_coaching);
                });
            })

            // Budget filter
            ->when(!empty($budget_range), function ($query) use ($budget_range) {
                $query->whereHas('userProfessional', function ($q) use ($budget_range) {
                    $q->where('budget_range', $budget_range);
                });
            })

            // Gender filter
            ->when(!empty($coach_gender), function ($query) use ($coach_gender) {
                $query->where('users.gender', $coach_gender);
            })

            // Experience lavel filter
            ->when(!empty($coach_experience_level), function ($query) use ($coach_experience_level) {
                $query->whereHas('userProfessional', function ($q) use ($coach_experience_level) {
                    $q->where('experience', $coach_experience_level);
                });
            })

            // Certified Coach filter
            ->when(!empty($only_certified_coach), function ($query) use ($only_certified_coach) {
                $query->where('users.is_verified', $only_certified_coach);
            })


            //      ->whereHas('userServicePackages', function ($query) use ($preferred_schedule) {
            //         $query->whereDate('booking_availability_end','>=', $preferred_schedule);
            //     })

            ->when(!empty($preferred_start_date_urgency), function ($query) use ($preferred_start_date_urgency) {
                $query->whereHas('userServicePackages', function ($q) use ($preferred_start_date_urgency) {
                    $today = \Carbon\Carbon::today();

                    if ($preferred_start_date_urgency == 1) {
                        // Immediate (within a week)
                        $q->whereDate('booking_availability_start', '<=', $today->copy()->addDays(7));
                    } elseif ($preferred_start_date_urgency == 2) {
                        // Soon (1–2 weeks)
                        $q->whereBetween('booking_availability_start', [
                            $today->copy()->addDays(8),
                            $today->copy()->addDays(14)
                        ]);
                    } elseif ($preferred_start_date_urgency == 4 && !empty($specific_date)) {
                        // Specific Date — exact match
                        $q->whereDate('booking_availability_start', '=', \Carbon\Carbon::parse($specific_date));
                    }
                    // ID 3 (Flexible) — no filter applied
                });
            })


            ->where('users.is_deleted', 0)
            ->orderBy('users.id', 'desc')
            ->get();


        // print_r($usersshow);die;
        // Fetch matching coach IDs
        $coachIds = $usersshow->pluck('id');

        if ($coachIds->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No matching coaches found.',
            ]);
        }

        // Prepare common request data (excluding coach_id)
        $data = $request->only([
            'looking_for',
            'coaching_category',
            'coach_subtype',
            'coach_type',
            'preferred_mode_of_delivery',
            'location',
            'coaching_goal',
            'language_preference',
            'preferred_communication_channel',
            'learner_age_group',
            'preferred_teaching_style',
            'budget_range',
            'preferred_schedule',
            'coach_gender',
            'coach_experience_level',
            'only_certified_coach',
            'preferred_start_date_urgency',
            'special_requirements',
            'share_with_coaches',
        ]);

        $data['user_id'] = $user->id; // current user making the request
        $data['language_preference'] = json_encode($request->language_preference);
        $createdRequests = [];

        $data['coaching_category'] = $data['preferred_teaching_style'];
        unset($data['preferred_teaching_style']);

        $data['looking_for'] = $data['coach_type'];
        unset($data['coach_type']);

        if ($share_with_coaches == 1) {
            foreach ($coachIds as $coachId) {
                $data['coach_id'] = $coachId;

                // Create coaching request
                $coachingRequest = CoachingRequest::create($data);
                $createdRequests[] = $coachingRequest;




                // Fetch full data for PDF
                $coachingRequestData = CoachingRequest::with([
                    'user',
                    'coach',
                    'coachingCategory',
                    'coachingSubCategory',
                    'delivery_mode',
                    'communicationChannel',
                    'ageGroup',
                    'coachingCat',
                    'budgetRange',
                    'coachExperience',
                    'dateUrgency',
                    'user.languages.languagename',
                ])->find($coachingRequest->id);

                $userData = $coachingRequestData->user;


                $pdfData = [


                    'type_of_coaching' => $coachingRequestData->coachingCategory->type_name ?? 'N/A',
                    'sub_coaching_category' => $coachingRequestData->coachingSubCategory->subtype_name ?? 'N/A',
                    'preferred_mode_of_delivery' => $coachingRequestData->delivery_mode->mode_name ?? 'N/A',

                    'location' => $coachingRequestData->location
                        ? MasterCountry::where('country_id', $coachingRequestData->location)->value('country_name')
                        : 'N/A',
                    'goal_or_objective' => $coachingRequestData->coaching_goal ?? 'N/A',
                    'language_preference' => $userData->languages
                        ? implode(', ', $userData->languages->pluck('languagename.language')->toArray())
                        : 'N/A',
                    'preferred_communication_channel' => $coachingRequestData->communicationChannel->category_name ?? 'N/A',
                    'target_age_group' => $coachingRequestData->ageGroup->group_name ?? 'N/A',
                    'preferred_teaching_style' => $coachingRequestData->coachingCat->category_name ?? 'N/A',
                    'budget_range' => $coachingRequestData->budgetRange->budget_range ?? 'N/A',
                    'coach_gender' => match ($coachingRequestData->coach_gender) {
                        1 => 'Male',
                        2 => 'Female',
                        default => 'Any',
                    },
                    'coach_experience_level' => $coachingRequestData->coachExperience->experience_level ?? 'N/A',
                    'only_certified_coach' => $coachingRequestData->only_certified_coach ? 'Yes' : 'No',
                    'preferred_start_date_urgency' => $coachingRequestData->dateUrgency->prefer_start_date ?? 'N/A',
                    'special_requirements' => $coachingRequestData->special_requirements ?? 'Optional',
                    'share_with_coaches' => $coachingRequestData->share_with_coaches ? 'Yes' : 'No',
                ];

                // Generate PDF
                $pdf = Pdf::loadView('pdf.coaching_request', [
                    'user' => $userData,
                    'data' => $pdfData,
                ]);




                // Save PDF in storage (public/uploads/coaching_requests/)
                $pdfPath = 'uploads/coaching_requests/request_' . $coachingRequest->id . '.pdf';
                Storage::disk('public')->put($pdfPath, $pdf->output());

                // 📩 Create message entry for the coach
                Message::create([
                    'sender_id'    => $user->id,
                    'receiver_id'  => $coachId,
                    'message'      => 'Click to view Coaching Request',
                    'message_type' => 2, // 2 = coaching request
                    'is_read'      => 0,
                    'document'     => $pdfPath, // ⚙️ Add this new column (explained below)
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Coaching request submitted and PDF sent to coaches.',
                'data' => $createdRequests
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Search the particular coach successfully',
                'data' => $usersshow
            ]);
        }
    }

    public function cochingRequestSend12(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'coach_type' => 'required|integer',
            'coach_subtype' => 'nullable|integer',
            // 'preferred_mode_of_delivery' => 'nullable|integer',
            // 'location' => 'nullable|integer',
            // 'coach_gender' => 'nullable|string|in:male,female,other',
            // 'learner_age_group' => 'nullable|integer',
            // 'preferred_teaching_style' => 'nullable|integer',
            // 'only_certified_coach' => 'nullable|boolean',
            // 'coach_experience_level' => 'nullable|integer',
            // 'language_preference' => 'nullable|array',
            // 'budget_range' => 'nullable|string',
            // 'preferred_communication_channel' => 'nullable|integer',
            // 'preferred_start_date_urgency' => 'nullable|integer',
            // 'preferred_schedule' => 'nullable|date',
            // 'share_with_coaches' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $share_with_coaches = $request->share_with_coaches;
        $user_type = 3; // coach

        // ✅ Start building query
        $usersQuery = User::with([
            'services',
            'languages',
            'userServicePackages',
            'userProfessional.coachType',
            'userProfessional.coachSubtype',
            'coachsubtypeuser',
            'country',
        ])->where('users.user_type', $user_type)
            ->where('users.is_deleted', 0);

        if ($share_with_coaches == 1) {
            // 🔹 Case 1: Share with multiple coaches
            $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                $query->where('coach_type', $request->coach_type);
            });

            if (!empty($request->coach_subtype)) {
                $usersQuery->whereHas('coachsubtypeuser', function ($query) use ($request) {
                    $query->where('coach_subtype_id', $request->coach_subtype);
                });
            }
        } else {

            if (!empty($request->coach_type)) {
                $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                    $query->where('coach_type', $request->coach_type);
                });
            }

            if (!empty($request->coach_subtype)) {
                $usersQuery->whereHas('coachsubtypeuser', function ($query) use ($request) {
                    $query->where('coach_subtype_id', $request->coach_subtype);
                });
            }

            if (!empty($request->preferred_mode_of_delivery)) {
                $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                    $query->where('delivery_mode', $request->preferred_mode_of_delivery);
                });
            }

            if (!empty($request->location)) {
                $usersQuery->where('users.country_id', $request->location);
            }

            if (!empty($request->learner_age_group)) {
                $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                    $query->where('age_group', $request->learner_age_group);
                });
            }

            if (!empty($request->preferred_teaching_style)) {
                $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                    $query->where('coaching_category', $request->preferred_teaching_style);
                });
            }

            if (!empty($request->coach_experience_level)) {
                $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                    $query->where('experience', $request->coach_experience_level);
                });
            }

            if (!empty($request->language_preference)) {
                $usersQuery->whereHas('languages', function ($query) use ($request) {
                    $query->whereIn('language_id', $request->language_preference);
                });
            }

            if (!empty($request->coach_gender)) {
                $usersQuery->where('users.gender', $request->coach_gender);
            }

            if (!empty($request->preferred_communication_channel)) {
                $usersQuery->whereHas('userServicePackages', function ($query) use ($request) {
                    $query->where('communication_channel', $request->preferred_communication_channel);
                });
            }

            if (!empty($request->budget_range)) {
                $usersQuery->whereHas('userProfessional', function ($query) use ($request) {
                    $query->where('budget_range', $request->budget_range);
                });
            }

            if (!empty($request->preferred_schedule)) {
                $usersQuery->whereHas('userServicePackages', function ($query) use ($request) {
                    $query->whereDate('booking_availability_end', '>=', $request->preferred_schedule);
                });
            }

            if (!empty($request->preferred_start_date_urgency)) {
                $usersQuery->whereHas('userServicePackages', function ($q) use ($request) {
                    $today = \Carbon\Carbon::today();

                    if ($request->preferred_start_date_urgency == 1) {
                        $q->whereDate('booking_availability_start', '<=', $today->copy()->addDays(7));
                    } elseif ($request->preferred_start_date_urgency == 2) {
                        $q->whereBetween('booking_availability_start', [
                            $today->copy()->addDays(8),
                            $today->copy()->addDays(14)
                        ]);
                    } elseif ($request->preferred_start_date_urgency == 4 && !empty($request->specific_date)) {
                        $q->whereDate('booking_availability_start', '=', \Carbon\Carbon::parse($request->specific_date));
                    }
                });
            }

            if (!empty($request->only_certified_coach)) {
                $usersQuery->where('users.is_verified', 1);
            }
        }

        $usersshow = $usersQuery->orderBy('users.id', 'desc')->get();
        $coachIds = $usersshow->pluck('id');

        if ($coachIds->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No matching coaches found.',
            ]);
        }

        // ✅ Prepare coaching request data
        $data = $request->only([
            'looking_for',
            'coaching_category',
            'coach_subtype',
            'coach_type',
            'preferred_mode_of_delivery',
            'location',
            'coaching_goal',
            'language_preference',
            'preferred_communication_channel',
            'learner_age_group',
            'preferred_teaching_style',
            'budget_range',
            'preferred_schedule',
            'coach_gender',
            'coach_experience_level',
            'only_certified_coach',
            'preferred_start_date_urgency',
            'special_requirements',
            'share_with_coaches',
        ]);

        $data['user_id'] = $user->id;
        $data['language_preference'] = json_encode($request->language_preference);

        // Fix field mapping
        $data['coaching_category'] = $data['preferred_teaching_style'];
        unset($data['preferred_teaching_style']);
        $data['looking_for'] = $data['coach_type'];
        unset($data['coach_type']);

        $createdRequests = [];

        foreach ($coachIds as $coachId) {
            $data['coach_id'] = $coachId;
            $coachingRequest = CoachingRequest::create($data);
            $createdRequests[] = $coachingRequest;
        }

        return response()->json([
            'status' => true,
            'message' => 'Coaching request submitted successfully',
            'data' => $createdRequests
        ]);
    }



    public function cochingRequestsListsUserDashboard(Request $request)
    {
        $user = Auth::user();
        // echo $user->id;die;

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $cochingRequestsList = CoachingRequest::with([
            'coach:id,first_name,last_name,display_name,profile_image,company_name'
        ])
            ->where('user_id', 73)
            ->where('is_active', 1)
            ->get()
            ->map(function ($request) {
                $avgRating = optional($request->coach->reviews()->where('is_deleted', 0))->avg('rating');
                $request->average_rating = round($avgRating, 1);
                return $request;
            });


        //$cochingRequestsList['reviews'] =  $user->reviews->avg('rating');
        return response()->json([
            'status' => true,
            'message' => 'Coaching request list',
            'data' => $cochingRequestsList
        ]);
    }


    public function addPackageRequest(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }


            $validated = $request->validate([
                'package_id'      => 'required|integer',
                'coach_id'        => 'required|integer',
                'amount'          => 'required|numeric',
                'slot_date_time'  => 'required|array|min:1',
                'slot_date_time.*' => 'array|size:2', // each must be [date, time]
            ]);

            $savedSlots = [];

            foreach ($validated['slot_date_time'] as $slot) {
                $session_date_start = $slot[0] ?? null; // date
                $slot_time_start    = $slot[1] ?? null; // time

                if (!$session_date_start || !$slot_time_start) {
                    continue; // skip invalid
                }

                $startDateTime = \Carbon\Carbon::parse($session_date_start . ' ' . $slot_time_start);
                $endDateTime   = (clone $startDateTime)->addMinutes($request->session_duration_minutes);

                $booking = new BookingPackages();
                $booking->package_id         = $validated['package_id'];
                $booking->coach_id           = $validated['coach_id'];
                $booking->user_id            = $user->id;
                $booking->session_date_start = $session_date_start;
                $booking->session_date_end   = $session_date_start;
                $booking->slot_time_start    = $slot_time_start;
                $booking->slot_time_end      = $endDateTime->format('H:i');

                $booking->amount             = $validated['amount'];
                $booking->delivery_mode      = $request->delivery_mode ?? null;
                $booking->save();
            }

            $savedSlots[] = [
                'package_id'         => $booking->package_id,
                'coach_id'           => $booking->coach_id,
                'user_id'            => $user->id,
                // 'slot_time_start'    => $booking->slot_time_start,
                // 'slot_time_end'      => $booking->slot_time_end,
                // 'session_date_start' => $booking->session_date_start,
                // 'session_date_end'   => $booking->session_date_end,
                'session_duration_minutes' => $booking->session_duration_minutes,
                'amount'             => $booking->amount,
            ];
            return response()->json([
                'status'  => true,
                'message' => 'Coach request submitted successfully.',
                'data'    => [
                    'slots' => $savedSlots,
                    'user_details' => [
                        'id'         => $user->id,
                        'email'      => $user->email,
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                        'user_type'  => $user->user_type,
                        'country_id' => $user->country_id,
                        'profile_image' => $user->profile_image
                            ? url('public/uploads/profile_image/' . $user->profile_image)
                            : '',
                    ]
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
