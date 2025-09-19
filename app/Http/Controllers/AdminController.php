<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\BookingPackages;
use App\Models\Message;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            //print_r(Auth::guard("admin")->attempt(["email" => $request->email,"password" => $request->password,'user_type'=>'1']));die();
            if (Auth::guard("admin")->attempt(["email" => $request->email, "password" => $request->password])) {

                $user = Auth::guard("admin")->user();

                if ($user->user_type != 1) {
                    Auth::guard("admin")->logout();
                    return redirect()->back()->with("warning", "You are not authorized as admin.");
                }
                if ($user->is_deleted == 1) {
                    Auth::guard("admin")->logout();
                    return redirect()->back()->with("warning", "Your account is not activated by administrator.");
                }

                return redirect()->route("admin.dashboard");
            } else {
                echo "Credentails do not matches our record.";
                Session::flash('message', "Credentails do not matches our record");
                return redirect()->back()->withErros(["email" => "Credentails do not matches our record."]);
            }
        }
        if (Auth::guard("admin")->user()) {
            $user = Auth::guard("admin")->user();

            if ($user->user_type != 1) {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }
            return redirect()->route("admin.dashboard");
        } else {
            return view('admin.login');
        }
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    // public function newDashboard()
    // {
    //     if(Auth::guard("admin")->user())
    //     {
    //         $user = Auth::guard("admin")->user();

    //         if ($user->user_type != 1)
    //         {
    //             Auth::guard("admin")->logout();
    //             return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
    //         }

    //         $userCount = User::where('user_status', '=', 1)
    //                                      ->where('user_type','2')
    //                                      ->where('is_deleted','0')
    //                                      ->count();

    //         $coachCount = User::where('user_status', '=', 1)
    //                                     ->where('user_type','3')
    //                                     ->where('is_deleted','0')
    //                                     ->count();

    //         $totalBooking = BookingPackages::count();

    //                                     // echo $totalBooking;die;

    //         $today = Carbon::now();
    //        $todayBooking = BookingPackages::whereDate('created_at', $today)
    //                            ->distinct('txn_id')
    //                            ->count('txn_id');

    //                                     // echo $todayBooking;die;
    //         return view('admin.new-dashboard', compact('userCount','coachCount','totalBooking','todayBooking'));
    //     }
    //     else
    //     {
    //         return view('admin.login');
    //     }
    //     // return view('admin.new-dashboard');
    // }


     public function newDashboard()
    {
        if (Auth::guard("admin")->user()) {
            $user = Auth::guard("admin")->user();

            if ($user->user_type != 1) {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }

            $totalUser = User::where('user_status', '=', 1)
                ->whereIn('user_type', [2, 3])
                ->where('is_verified', '1')
                ->where('user_status', '1')
                ->where('is_deleted', '0')
                ->count();

            $todayUsers = User::where('user_status', 1)
                ->whereIn('user_type', [2, 3])
                ->where('is_verified', '1')
                ->where('user_status', '1')
                ->where('is_deleted', 0)
                ->whereDate('created_at', Carbon::today())
                ->count();

            $monthlyActiveUsers = User::where('user_status', 1)
                ->whereIn('user_type', [2, 3])
                ->where('is_verified', '1')
                ->where('user_status', '1')
                ->where('is_deleted', 0)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();


            $totalMessages = Message::count();


            $totalBooking = BookingPackages::count();

            // echo $totalBooking;die;

            $today = Carbon::now();
            $todayBooking = BookingPackages::whereDate('created_at', $today)
                ->distinct('txn_id')
                ->count('txn_id');








            // 🔹 Last 6 months data
            $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();

            $userStats = User::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw("SUM(CASE WHEN user_type = 2 THEN 1 ELSE 0 END) as coaches"),
                DB::raw("SUM(CASE WHEN user_type = 3 THEN 1 ELSE 0 END) as users")
            )
                ->where('user_status', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->where('created_at', '>=', $sixMonthsAgo)
                ->groupBy('month')
                ->orderByRaw("MIN(created_at)")
                ->get();

            // Format data for Chart.js
            $months = $userStats->pluck('month');
            $coaches = $userStats->pluck('coaches');
            $users   = $userStats->pluck('users');




            $totalCoachUsers = User::where('user_status', 1)
                ->where('user_type', 3)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->count();

            // Pro coach users (with subscription)
            $proCoachUsers = User::where('user_status', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->where('user_type', 3)
                ->whereIn('id', function ($query) {
                    $query->select('user_id')->from('user_subscription');
                })
                ->count();

            // Free users → total - pro
            $freeCoachUsers = $totalCoachUsers - $proCoachUsers;


            $totalRevenue = DB::table('user_subscription')
                ->where('is_active', 1)
                ->sum('amount');


$totalCoachAvgRating = DB::table('review')
    ->join('users', 'review.coach_id', '=', 'users.id')
    ->where('users.user_status', 1)
    ->where('users.is_verified', 1)
    ->where('users.is_deleted', 0)
    ->where('users.user_type', 3) // only coaches
    ->avg('review.rating');


// Coach filter

$topCoaches = User::select(
        'users.*',
        DB::raw('(SELECT SUM(amount)
                  FROM transactions
                  WHERE transactions.coach_id = users.id
                  AND transactions.status = "succeeded") as total_revenue'),
        DB::raw('(SELECT AVG(rating)
                  FROM review
                  WHERE review.coach_id = users.id) as avg_rating'),
        DB::raw('(SELECT COUNT(*)
                  FROM favorite_coach
                  WHERE favorite_coach.coach_id = users.id) as favorite_count'),
        DB::raw('(SELECT COUNT(*)
                  FROM booking_packages
                  WHERE booking_packages.coach_id = users.id) as session_count')

    )
    ->where('user_status', 1)
    ->where('is_verified', 1)
    ->where('is_deleted', 0)
    ->where('user_type', 3)
    ->orderByDesc('total_revenue') // first order by revenue
    ->orderByDesc('avg_rating')    // then by rating
    ->orderByDesc('session_count')
    ->orderByDesc('favorite_count') // order by most favorited
    ->get();





$topEngagedCoaches = User::select(
        'users.*',
        DB::raw('(SELECT COUNT(*)
                  FROM booking_packages
                  WHERE booking_packages.coach_id = users.id) as session_count'),
        DB::raw('(SELECT COUNT(*)
                  FROM messages
                  WHERE messages.receiver_id = users.id) as message_count'),
        // DB::raw('(SELECT COUNT(*)
        //           FROM matches
        //           WHERE matches.coach_id = users.id) as match_count')
    )
    ->where('user_status', 1)
    ->where('is_verified', 1)
    ->where('is_deleted', 0)
    ->where('user_type', 3)
    ->orderByDesc('session_count')   // order priority 1
    ->orderByDesc('message_count')   // order priority 2
    // ->orderByDesc('match_count')     // order priority 3
    ->limit(5)
    ->get();


$totalCoachingCompleted = DB::table('booking_packages')
    ->whereDate('session_date_end', '<', now()) // completed sessions
    ->count();


$activeCoachingThisMonth = DB::table('booking_packages')
    ->whereMonth('session_date_start', now()->month)
    ->whereYear('session_date_start', now()->year)
    ->where('session_date_start', '<=', now())
    ->where('session_date_end', '>=', now())
    ->count();



            return view('admin.new-dashboard', compact(
                'totalMessages',
                'totalUser',
                'todayUsers',
                'monthlyActiveUsers',
                'totalBooking',
                'todayBooking',
                'totalCoachAvgRating',
                'months',
                'coaches',
                'freeCoachUsers',
                'proCoachUsers',
                'totalRevenue',
                'users',
                'topCoaches',
                'topEngagedCoaches',
                'totalCoachingCompleted',
                'activeCoachingThisMonth'
            ));




            // echo $todayBooking;die;
            //return view('admin.new-dashboard', compact('totalMessages','totalUser', 'todayUsers', 'monthlyActiveUsers', 'totalBooking', 'todayBooking'));
        } else {
            return view('admin.login');
        }
        // return view('admin.new-dashboard');
    }

    public function dashboard()
    {
        if (Auth::guard("admin")->user()) {
            $user = Auth::guard("admin")->user();

            if ($user->user_type != 1) {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }

            $userCount = User::where('user_status', '=', 1)
                ->where('user_type', '2')
                ->where('is_deleted', '0')
                ->count();

            $coachCount = User::where('user_status', '=', 1)
                ->where('user_type', '3')
                ->where('is_deleted', '0')
                ->count();

            $totalBooking = BookingPackages::count();

            // echo $totalBooking;die;

            $today = Carbon::now();
            $todayBooking = BookingPackages::whereDate('created_at', $today)
                ->distinct('txn_id')
                ->count('txn_id');

            // echo $todayBooking;die;
            return view('admin.dashboard', compact('userCount', 'coachCount', 'totalBooking', 'todayBooking'));
        } else {
            return view('admin.login');
        }
    }
    public function getstate(Request $request)
    {
        $state = DB::table('master_state')->where('state_country_id', '=', $request->country_id)->orderBY('state_name', 'asc')->get();
        $data = compact('state');
        return response()->json($data);
    }

    public function getcity(Request $request)
    {
        $city = DB::table('master_city')->where('city_state_id', '=', $request->state_id)->orderBY('city_name', 'asc')->get();
        $data = compact('city');
        return response()->json($data);
    }

    public function getsubType(Request $request)
    {
        $city = DB::table('coach_subtype')->where('coach_type_id', '=', $request->coach_type_id)->orderBY('subtype_name', 'asc')->get();
        $data = compact('city');
        return response()->json($data);
    }
}
