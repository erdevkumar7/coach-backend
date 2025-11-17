<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Appointment;
use App\Models\UserSubscription;

class BookingController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $coaches = UserSubscription::orderBy('id', 'DESC')->paginate(10);
        // dd($coaches);
        return view('admin.coach_booking',compact('coaches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // Show calendar page
    public function showCalendar($id)
    {
        $coach = User::findOrFail($id);
        return view('admin.booking_calendar', compact('coach'));
    }

    // Return events JSON for calendar
    public function calendarData($coachId)
    {
        $appointments = Appointment::with('user')
            ->where('coach_id', $coachId)
            ->get();

        $events = $appointments->map(function ($appointment) {
                return [
                    'title' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
                    'start' => \Carbon\Carbon::parse($appointment->start_time)->toIso8601String(),
                    'end'   => \Carbon\Carbon::parse($appointment->finish_time)->toIso8601String(),
                    'extendedProps' => [
                        'email' => $appointment->user->email,
                        'status' => $appointment->status
                    ]
                ];
        });

        return response()->json($events);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
