<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BookingPackages;
use App\Models\CoachingRequest;
use App\Models\FavoriteCoach;
use App\Models\Message;
use App\Models\PackageHistory;
use App\Models\Review;
use App\Models\User;
use App\Models\UserServicePackage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function updateProfileImage(Request $request)
    {
        try {
            $user = Auth::user(); //  JWT Authenticated User
            $id = $user->id;

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive.',
                ], 403);
            }

            $user = User::where('id', $id)
                ->where('user_status', 1)
                ->where('user_type', $request->user_type)
                ->first();

            if (!$request->hasFile('profile_image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided.'
                ], 400);
            }

            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = "pro" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/profile_image'), $imageName);
                $user->profile_image = $imageName;
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully.',
                'profile_image'  => $user->profile_image
                    ? url('public/uploads/profile_image/' . $user->profile_image)
                    : '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token not valid or other error.',
                'error'   => $e->getMessage()
            ], 401);
        }
    }

    public function coachDashboard(Request $request)
    {
        $user = Auth::user();
        $id   = $user->id;
        $id   = 72;

        try {
            $now = Carbon::now()->format('Y-m-d H:i:s');

            // $completedPackages = BookingPackages::where('coach_id', $id)
            //     ->whereRaw("STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s') < ?", [$now])
            //     ->count();

            $newCoachingRequest = CoachingRequest::where('coach_id', $id)
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->count();


            $confirmedBookings = BookingPackages::where('coach_id', $id)
                ->where('status',  1)
                ->count();

            $completedPackages = BookingPackages::where('coach_id', $id)
                ->where('status', '!=', 3)
                ->whereRaw("CONCAT(session_date_end, ' ', slot_time_end) < ?", [Carbon::now()])
                ->count();





            $inProgressOrders = BookingPackages::with([
                'user.country',
                'user.userProfessional.coachType',
                'coachPackage',
            ])
                ->where('coach_id', $id)
                ->where('status', '!=', 3)
                ->whereRaw("? BETWEEN STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s')
                           AND STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s')", [$now])
                ->get();

            $inProgressCount = $inProgressOrders->count();


            $upcomingBookings = BookingPackages::with([
                'user.country',
                'user.userProfessional.coachType',
                'coachPackage',
            ])
                ->where('coach_id', $id)
                ->where('status', '!=', 3)
                ->whereRaw("STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s') > ?", [$now])
                ->limit(3)
                ->get();


            $totalEarning = BookingPackages::where('coach_id', $id)->where('status', '!=', 3)->sum('amount');


            $upcomingResults = $upcomingBookings->map(function ($req) {
                $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);

                return [
                    'booking_id'        => $req->id,
                    'first_name'        => $req->user->first_name ?? null,
                    'last_name'         => $req->user->last_name ?? null,
                    'display_name'      => $req->user->display_name ?? null,
                    'user_type'         => $req->user->user_type ?? null,
                    'package_title'     => $req->coachPackage->title ?? null,
                    'profile_image'     => $req->user->profile_image
                        ? url('public/uploads/profile_image/' . $req->user->profile_image)
                        : '',
                    'session_date_start' => $req->session_date_start,
                    'slot_time_start'    => $req->slot_time_start,
                    'session_date_end'   => $req->session_date_end,
                    'slot_time_end'      => $req->slot_time_end,
                    'country'            => $req->user->country->country_name ?? null,
                    'status'             => 'confirmed',
                    // 'starts_in_minutes'  => $startDateTime->isFuture()
                    //     ? Carbon::now()->diffInMinutes($startDateTime)
                    //     : 0,
                ];
            });

            $unread_messages = Message::where('sender_id' , $id)->where('is_read', 0)->count();
            $average_rating = Review::where('coach_id' , $id)->where('is_deleted', 0)->avg('rating');
            $average_rating = (float) number_format($average_rating, 2, '.', '');

            $no_of_favorite = FavoriteCoach::where('coach_id' , $id)->count();
            return response()->json([
                'status'  => true,
                'message' => 'Dashboard data fetched successfully',
                'data'    => [
                    'completed_bookings'     => $completedPackages,
                    'confirmed_bookings'     => $confirmedBookings,
                    'in_progress_count'   => $inProgressCount,
                    'new_requests'        => $newCoachingRequest,
                    'total_earning'       => $totalEarning,
                    // 'in_progress_bookings'=> $inProgressResults,
                    'upcoming_sessions'   => $upcomingResults,
                    'unread_messages'       => $unread_messages,
                    'average_rating'       => $average_rating,
                    'no_of_favorite'       => $no_of_favorite,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function submitPackageViewCount(Request $request)
    {
        try {
            $request->validate([
                'package_id'  => 'required|integer|exists:user_service_packages,id',
                'user_id'   => 'nullable|integer|exists:users,id',
            ]);

            $package = UserServicePackage::find($request->package_id);
            $user = Auth::user();

            // Determine viewer info
            $viewerId   = $request->user_id;
            $viewerType = $viewerId ? 'user' : 'guest';

            // Check if same user already viewed this package
            $existing = PackageHistory::where('package_id', $package->id)
                ->where('viewer_id', $viewerId)
                ->first();

            if ($existing) {
                // Increment the view count
                $existing->increment('view_count');
            } else {
                // Insert new record
                PackageHistory::create([
                    'coach_id'    => $package->coach_id,
                    'package_id'  => $package->id,
                    'viewer_id'   => $viewerId,
                    'viewer_type' => $viewerType,
                    'view_count'  => 1,
                ]);
            }


            return response()->json([
                'success' => true,
                'message' => 'Package view recorded successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record package view.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function coachServicePerformances(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 403);
            }

            if ($user->user_type != 3 || $user->is_deleted != 0 || $user->is_verified != 1 || $user->user_status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.',
                ], 403);
            }

            // Get all packages of this coach
            $packages = UserServicePackage::where('coach_id', $user->id)->select('id', 'title', 'package_status')->get();

            // Add view count for each package
            $packages->map(function ($package) {
                $package->view_count = PackageHistory::where('package_id', $package->id)
                    ->sum('view_count'); // sum because same user can view multiple times
                $package->total_earning = BookingPackages::where('package_id', $package->id)->where('status', 1)
                    ->sum('amount');
                $package->confirmed_booking = BookingPackages::where('package_id', $package->id)->where('status', 1)
                    ->count();
                return $package;
            });

            return response()->json([
                'success' => true,
                'data' => $packages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function atAGlaceUserDashboard(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 403);
            }

            if ($user->user_type != 2 || $user->is_deleted != 0 || $user->is_verified != 1 || $user->user_status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.',
                ], 403);
            }

            $atAGlance = [];
            // Get all packages of this coach
            $atAGlance['total_coach_matches'] = CoachingRequest::where('user_id', $user->id)->count();
            $atAGlance['total_coaching_request'] = CoachingRequest::where('user_id', $user->id)->count();
            $atAGlance['unread_message'] = Message::where('sender_id', $user->id)->where('is_read', 0)->count();

            $now = now(); // current date and time

            $atAGlance['upcoming_session'] = BookingPackages::select('session_date_start', 'slot_time_start')->where('user_id', $user->id)
                ->where(function ($query) use ($now) {
                    $query->where('session_date_start', '>', $now->toDateString())
                        ->orWhere(function ($q) use ($now) {
                            $q->where('session_date_start', $now->toDateString())
                                ->where('slot_time_start', '>', $now->toTimeString());
                        });
                })
                ->orderBy('session_date_start', 'asc')
                ->orderBy('slot_time_start', 'asc')
                ->first();


            $atAGlance['active_coaching'] = BookingPackages::where('user_id', $user->id)
                ->where(function ($query) use ($now) {
                    $query->where('session_date_start', '<=', $now->toDateString())
                        ->where('session_date_end', '>=', $now->toDateString());
                })
                ->where(function ($query) use ($now) {
                    $query->where('slot_time_start', '<=', $now->toTimeString())
                        ->where('slot_time_end', '>=', $now->toTimeString());
                })
                ->first();




            // Add view count for each package
            // $packages->map(function ($package) {
            //     $package->view_count = PackageHistory::where('package_id', $package->id)
            //         ->sum('view_count'); // sum because same user can view multiple times
            //     $package->total_earning = BookingPackages::where('package_id', $package->id)->where('status', 1)
            //         ->sum('amount');
            //     $package->confirmed_booking = BookingPackages::where('package_id', $package->id)->where('status', 1)
            //         ->count();
            //     return $package;
            // });

            return response()->json([
                'success' => true,
                'data' => $atAGlance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function userActivityLog(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.',
                ], 403);
            }

            if ($user->user_type != 2 || $user->is_deleted != 0 || $user->is_verified != 1 || $user->user_status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied.',
                ], 403);
            }

            // Get latest 5 activity logs
            $activity_logs = ActivityLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            if ($activity_logs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => "No activity found",
                ]);
            }

            // Add "time ago" for each log
            $activity_logs->transform(function ($log) {
                $log->time_ago = Carbon::parse($log->created_at)->diffForHumans();
                return $log;
            });

            return response()->json([
                'success' => true,
                'data' => $activity_logs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function userDashboard78(Request $request)
    {
        $user = Auth::user();
        $id = $user->id;
        // echo $id;die;
        try {
            $now = Carbon::now();

            $completedPackages = BookingPackages::where('coach_id', $id)
                ->whereRaw("
                    STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s') < ?
                ", [$now])
                ->orderBy('booking_packages.id', 'desc')
                ->count();

            $confirmedOrders = BookingPackages::where('coach_id', $id)
                ->whereRaw("STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s') > ?", [$now])
                ->count();

            $inProgressOrders = BookingPackages::with([
                'user.country',
                'user.userProfessional.coachType',
                'coachPackage',
            ])
                ->where('coach_id', $id)
                ->whereRaw("? BETWEEN STR_TO_DATE(CONCAT(session_date_start, ' ', slot_time_start), '%Y-%m-%d %H:%i:%s')
                     AND STR_TO_DATE(CONCAT(session_date_end, ' ', slot_time_end), '%Y-%m-%d %H:%i:%s')", [$now])
                ->get();
            $countshow = $inProgressOrders->count();
            //   print_r($inProgressOrders);die;
            $newCoachingRequest = CoachingRequest::where('coach_id', $id)->count();

            $totalEarning = BookingPackages::where('coach_id', $id)->sum('amount');

            //  echo $inProgressOrders;die;



            $results = $inProgressOrders->getCollection()->map(function ($req) use ($relation, $now) {
                $show_relation = $relation;


                // $startDateTime = Carbon::parse($req->session_date_start . ' ' . $req->slot_time_start);
                // $endDateTime   = Carbon::parse($req->session_date_end . ' ' . $req->slot_time_end);
                // $endDate       = Carbon::parse($req->session_date_end)->endOfDay();


                // $status = null;
                // if ($now->between($startDateTime, $endDateTime)) {
                //     $status = 'in-progress';
                // } elseif ($now->lt($startDateTime)) {
                //     $status = 'confirmed';
                // }

                // // Sessions left
                // $sessionLeft = $now->lte($endDate)
                //     ? $now->diffInDays($endDate)
                //     : 0;

                return [
                    'id'                => $req->$show_relation->id ?? null,
                    'booking_id'        => $req->id ?? null,
                    'first_name'        => $req->$show_relation->first_name ?? null,
                    'last_name'         => $req->$show_relation->last_name ?? null,
                    'user_type'         => $req->$show_relation->user_type ?? null,
                    'display_name'      => $req->$show_relation->display_name ?? null,
                    'package_title'     => $req->coachPackage->title ?? null,
                    'profile_image'     => $req->$show_relation->profile_image
                        ? url('public/uploads/profile_image/' . $req->$show_relation->profile_image)
                        : '',
                    'session_date_start' => $req->session_date_start ?? null,
                    'slot_time_start'    => $req->slot_time_start ?? null,
                    'session_date_end'   => $req->session_date_end ?? null,
                    'slot_time_end'      => $req->slot_time_end ?? null,
                    'country'            => $req->$show_relation->country->country_name ?? null,
                    'status'             => $status ?? null,
                    'session_left'       => $status
                        ? ($status === 'confirmed'
                            ? 'session not started yet'
                            : max(round($sessionLeft, 0) - 1, 0))
                        : null,
                    // 'created_at'         => $req->created_at ?? null,
                    // 'updated_at'         => $req->updated_at ?? null,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'Support request added successfully',
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
