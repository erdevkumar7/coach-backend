<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\BookingPackages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post'))
        {
            //print_r(Auth::guard("admin")->attempt(["email" => $request->email,"password" => $request->password,'user_type'=>'1']));die();
            if (Auth::guard("admin")->attempt(["email" => $request->email,"password" => $request->password])) {

                $user = Auth::guard("admin")->user();

                if ($user->user_type != 1) 
                {
                    Auth::guard("admin")->logout();
                    return redirect()->back()->with("warning", "You are not authorized as admin.");
                }
                if ($user->is_deleted == 1) 
                {
                    Auth::guard("admin")->logout();
                    return redirect()->back()->with("warning", "Your account is not activated by administrator.");
                }
            
                return redirect()->route("admin.dashboard");
            }else{
                echo "Credentails do not matches our record.";
                 Session::flash('message', "Credentails do not matches our record");
                return redirect()->back()->withErros(["email" => "Credentails do not matches our record."]);
            }
        }
        if(Auth::guard("admin")->user())
        {
            $user = Auth::guard("admin")->user();
        
            if ($user->user_type != 1) 
            {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }
            return redirect()->route("admin.dashboard");
        }
        else
        {
            return view('admin.login');
        }
        
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function newDashboard()
    {
        if(Auth::guard("admin")->user())
        {
            $user = Auth::guard("admin")->user();
        
            if ($user->user_type != 1) 
            {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }
           
            $userCount = User::where('user_status', '=', 1)
                                         ->where('user_type','2')
                                         ->where('is_deleted','0')
                                         ->count();
                                        
            $coachCount = User::where('user_status', '=', 1)
                                        ->where('user_type','3')
                                        ->where('is_deleted','0')
                                        ->count();     

            $totalBooking = BookingPackages::count();                              

                                        // echo $totalBooking;die;

            $today = Carbon::now();
           $todayBooking = BookingPackages::whereDate('created_at', $today)
                               ->distinct('txn_id')
                               ->count('txn_id');

                                        // echo $todayBooking;die;
            return view('admin.new-dashboard', compact('userCount','coachCount','totalBooking','todayBooking'));
        }
        else
        {
            return view('admin.login');
        }
        // return view('admin.new-dashboard');
    }

    public function dashboard()
    {
        if(Auth::guard("admin")->user())
        {
            $user = Auth::guard("admin")->user();
        
            if ($user->user_type != 1) 
            {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }
           
            $userCount = User::where('user_status', '=', 1)
                                         ->where('user_type','2')
                                         ->where('is_deleted','0')
                                         ->count();
                                        
            $coachCount = User::where('user_status', '=', 1)
                                        ->where('user_type','3')
                                        ->where('is_deleted','0')
                                        ->count();     

            $totalBooking = BookingPackages::count();                              

                                        // echo $totalBooking;die;

            $today = Carbon::now();
           $todayBooking = BookingPackages::whereDate('created_at', $today)
                               ->distinct('txn_id')
                               ->count('txn_id');

                                        // echo $todayBooking;die;
            return view('admin.dashboard', compact('userCount','coachCount','totalBooking','todayBooking'));
        }
        else
        {
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


?>