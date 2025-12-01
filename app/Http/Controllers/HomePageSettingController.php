<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeSetting;
use App\Models\Contact;
use App\Models\AboutSetting;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\SocialMedia;
use App\Models\CoachingRequest;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\BookingPackages;
use App\Models\AdminCoachChat;
use Carbon\Carbon;
use App\Events\AdminMessageSent;




class HomePageSettingController extends Controller
{

    // public function SubscriptionPlanSection(Request $request)
    // {
    //     $plan_section = DB::table('home_settings')->first();
    //     if ($request->isMethod('post')) {
    //         $homeSetting = $plan_section 
    //             ? HomeSetting::find($plan_section->id)
    //             : new HomeSetting();

    //         $homeSetting->plan_title = $request->plan_title;
    //         $homeSetting->plan_subtitle = $request->plan_subtitle;
    //         $homeSetting->plan_description = $request->plan_description;
    //         $homeSetting->updated_at = now();

    //         if (!$plan_section) {
    //             $homeSetting->created_at = now();
    //         }

    //         $homeSetting->save();

    //         return redirect()->route("admin.SubscriptionPlanSection")
    //             ->with("success", "Subscription Plan Section updated successfully.");
    //     }

    //     return view('admin.subscription_plan_section', compact('plan_section'));
    // }

    public function manage($type)
    {
        $section = HomeSetting::where('section_name', $type)->first();
        return view('admin.subscription_plan_section', compact('section', 'type'));
    }

    public function manageupdate(Request $request, $type)
    {
        $section = HomeSetting::firstOrNew(['section_name' => $type]);

        if ($type === 'plan') {
            $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->subtitle = $request->subtitle;
            $section->description = $request->description;
        }

        if ($type === 'global_partners') {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $section->title = $request->title;
        }

        if ($type === 'top' || $type === 'corporate') {
            $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->subtitle = $request->subtitle;
        }

        if ($type === 'middle_one' || $type === 'middle_two') {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->description = $request->description;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "middle" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/blog_files'), $imageName);
                $section->image = $imageName;
            }
        }

        if ($type === 'footer_one') {
            $request->validate([
                'description' => 'nullable|string',
            ]);
            $section->description = $request->description;
        }

        if ($type === 'footer_two') {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $section->title = $request->title;
        }      

       if ($type === 'category') {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->description = $request->description;
        }

        $section->section_name = $type;
        $section->save();

        return redirect()->route('admin.manage', $type)
            ->with('success', ucfirst(str_replace('_', ' ', $type)) . ' section updated successfully!');
    }

    public function contact(Request $request)
    {
        $contact = Contact::first(); 

        if ($request->isMethod('post')) {

            if (!$contact) {
                $contact = new Contact();
            }

            $contact->title = $request->title;
            $contact->subtitle = $request->subtitle;
            $contact->email = $request->email;
            $contact->address = $request->address;
            $contact->map_location = $request->map_location;
            $contact->latitude = $request->latitude;
            $contact->longitude = $request->longitude;
            $contact->business_hourse = $request->business_hourse;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "contact_" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/blog_files'), $imageName);
                $contact->image = $imageName;
            }

            $contact->save();

            return redirect()->route("admin.contact")
                ->with("success", "Contact details saved successfully.");
        }

        return view('admin.contact', compact('contact'));
    }

        public function about($type)
    {
        $section = AboutSetting::where('section_name', $type)->first();
        return view('admin.about_setting', compact('section', 'type'));
    }

       public function aboutupdate(Request $request, $type)
    {
        $section = AboutSetting::firstOrNew(['section_name' => $type]);

        if ($type === 'about_top') {
            $request->validate([
                'subtitle' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->subtitle = $request->subtitle;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = "about_top" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/blog_files'), $imageName);
                $section->image = $imageName;
            }
        }

       if ($type === 'jurney') {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->description = $request->description;

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoName = "about_video" . time() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path('/uploads/blog_files'), $videoName);
                $section->video = $videoName;
            }
        }
        if ($type === 'team') {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->description = $request->description;
        }

        $section->section_name = $type;
        $section->save();

        return redirect()->route('admin.about', $type)
            ->with('success', ucfirst(str_replace('_', ' ', $type)) . ' section updated successfully!');
    }

    public function teamMember()
    {
        $teamMember = DB::table('team_members')->orderBy('id', 'DESC')->paginate(20);
        return view('admin.teamMember', compact('teamMember'));
    }

        public function addteamMember(Request $request, $id = null)
    {
        $teamMember = $id ? TeamMember::find($id) : new TeamMember();

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|max:25',
                'designation' => 'required|max:50',
                'image' => 'nullable|mimes:jpg,jpeg,jfif,png,webp|max:2048',
                'description' => 'required|max:255',
            ]);

            $teamMember->name = $request->name;
            $teamMember->designation = $request->designation;
            $teamMember->description = $request->description;
            $teamMember->status = 1;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'teammember_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/blog_files'), $imageName);
                $teamMember->image = $imageName;
            }

            $teamMember->save();

            return redirect()->route('admin.teamMember')
                ->with('success', 'Team Member details saved successfully.');
        }

        return view('admin.addteamMember', compact('teamMember'));
    }

    public function DeleteTeamMember(Request $request)
    {
        if ($request->ids) {
            TeamMember::whereIn('id', $request->ids)->delete();
            return redirect()->back()->with('success', 'Selected Team Members deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Please select at least one member.');
        }
    }

        public function updateTeamMemberStatus(Request $request)
    {
        $teamMember = TeamMember::find($request->id);
        if ($teamMember) {
            $teamMember->status = $request->status;
            $teamMember->save();
            return response()->json(['status' => true, 'message' => 'Status updated successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Team Member not found.']);
        }
    }

    public function setting(Request $request)
    {
        $admin = Auth::user()->where('user_type', 1)->where('id', Auth::id())->first();

        if ($request->isMethod('post')) {
            if ($request->has('first_name') && $request->has('last_name')) {
                $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
                    'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $admin->first_name = $request->first_name;
                $admin->last_name = $request->last_name;
                $admin->email = $request->email;

                if ($request->hasFile('profile_image')) {
                    $profile_image = $request->file('profile_image');
                    $profile_imageName = 'profile_image_' . time() . '.' . $profile_image->getClientOriginalExtension();
                    $profile_image->move(public_path('uploads/blog_files'), $profile_imageName);
                    $admin->profile_image = $profile_imageName;
                }

                $admin->save();

                return redirect()->route('admin.setting')->with('success', 'Profile updated successfully.');
            }

            if ($request->has('current_password') && $request->has('new_password') && $request->has('new_password_confirmation')) {
                $request->validate([
                    'current_password' => 'required|string',
                    'new_password' => 'required|string|min:6|confirmed',
                ]);

                if (!Hash::check($request->current_password, $admin->password)) {
                    return redirect()->route('admin.setting')->withErrors(['current_password' => 'The Current password is incorrect.']);
                }

                //   if ($request->current_password === $request->new_password) {
                //     return redirect()->route('admin.setting')->withErrors(['new_password' => 'New password cannot be the same as the current password.']);
                //    }

                $admin->password = Hash::make($request->new_password);
                $admin->save();

                return redirect()->route('admin.setting')->with('success', 'Password updated successfully.');
            }
        }

        return view('admin.setting', compact('admin'));
    }

     public function socialmedia(Request $request)
    {
        $socialmedia = SocialMedia::first(); 

        if ($request->isMethod('post')) {

            if (!$socialmedia) {
                $socialmedia = new SocialMedia();
            }

            $socialmedia->facebook = $request->facebook;
            $socialmedia->twitter = $request->twitter;
            $socialmedia->linkedin = $request->linkedin;
            $socialmedia->instagram = $request->instagram;
            $socialmedia->youtube = $request->youtube;

            $socialmedia->save();

            return redirect()->route("admin.socialmedia")
                ->with("success", "Social Media saved successfully.");
        }

        return view('admin.socialmedia', compact('socialmedia'));
    }

        public function newsletter(Request $request)
    {

            if ($request->has('export') && $request->export == 'csv') {
                    $newsletter = DB::table('newsletters')
                                    ->orderBy('id', 'DESC')
                                    ->get(['email', 'created_at']);

                    $filename = "newsletter_list_" . date('Y-m-d') . ".csv";
                    $handle = fopen($filename, 'w+');

                    fputcsv($handle, ['S.No', 'Email', 'Subscribed Date']);

                    $i = 1; 

                    foreach ($newsletter as $row) {
                        fputcsv($handle, [
                            $i++,
                            $row->email,
                            $row->created_at ? date('d-m-Y', strtotime($row->created_at)) : '',
                        ]);
                    }

                    fclose($handle);

                    return response()->download($filename)->deleteFileAfterSend(true);
            }

        $newsletter = DB::table('newsletters')->orderBy('id', 'DESC')->paginate(20);
        return view('admin.newsletter', compact('newsletter'));
    }

        public function Deletenewsletter(Request $request)
    {
        if ($request->ids) {
            DB::table('newsletters')->whereIn('id', $request->ids)->delete();
            return redirect()->back()->with('success', 'Selected Newsletter deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Please select at least one Newsletter.');
        }
    }

    public function generalEnquiry()
    {
        $subQuery = DB::table('messages')
            ->selectRaw('MIN(id) as id')
            ->where('message_type', 1)
            ->groupBy('sender_id', 'receiver_id');

        $generalEnquiry = DB::table('messages')
            ->joinSub($subQuery, 'first_messages', function ($join) {
                $join->on('messages.id', '=', 'first_messages.id');
            })
            ->join('users as user', 'user.id', '=', 'messages.sender_id')
            ->join('users as coach', 'coach.id', '=', 'messages.receiver_id')
            ->select(
                'messages.*',
                'user.first_name as user_first_name',
                'user.last_name as user_last_name',
                'user.email as user_email',
                'coach.first_name as coach_first_name',
                'coach.last_name as coach_last_name',
                'coach.email as coach_email'
            )
            ->orderBy('messages.id', 'DESC')
            ->paginate(20);

        return view('admin.generalEnquiry', compact('generalEnquiry'));
    }

    public function supportRequest()
    {
        $supportRequest = DB::table('support_requests')->orderBy('id', 'DESC')->paginate(20);
        return view('admin.supportRequest', compact('supportRequest'));
    }

        public function getreport()
    {
        $reports = DB::table('chat_reports')
        ->join('users as reporter', 'reporter.id', '=', 'chat_reports.reported_by_id')
        ->join('users as reported', 'reported.id', '=', 'chat_reports.reported_against_id')
        ->select(
            'chat_reports.*',
            'reporter.first_name as reporter_first_name',
            'reporter.last_name as reporter_last_name',
            'reporter.email as reporter_email',
            'reported.first_name as reported_first_name',
            'reported.last_name as reported_last_name',
            'reported.email as reported_email'
        )
        ->orderBy('id', 'DESC')->paginate(20);
        return view('admin.getreport', compact('reports'));
    }

    public function reportstatus(Request $request, $id)
    {
        try {
            DB::table('chat_reports')
                ->where('id', $id)
                ->update(['status' => $request->status]);

            return redirect()->back()->with('success', 'Report status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
        public function AllcoachingRequest()
    {

        $coachingRequests = CoachingRequest::with([
                                                'coach.country',
                                                'coach.userProfessional.coachType',
                                                'coach.languages.languagename',
                                                'user.country',
                                                'user.userProfessional.coachType',
                                                'user.languages.languagename',
                                                'coachingCategory',
                                                'coachingSubCategory',
                                                'delivery_mode',
                                                'communicationChannel',
                                                'ageGroup',
                                                'budgetRange',
                                                'coachExperience',
                                                'dateUrgency',
                                                'lokingFor'])
                                                ->orderBy('coaching_request.id', 'desc')
                                                ->paginate(10); 

        return view('admin.AllcoachingRequest', compact('coachingRequests'));
    }

    public function DeletecoachingRequest(Request $request)
    {
        if ($request->ids) {
            CoachingRequest::whereIn('id', $request->ids)->delete();
            return redirect()->back()->with('success', 'Selected Request deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Please select at least one member.');
        }
    }

    public function CoachplanUpgrade(Request $request, $id = null)
    {
        $coach_id = $id;

          $plans = Subscription::where('is_active', 1)
            ->where('plan_amount', '>', 0)
            ->get();


        return view('admin.CoachplanUpgrade', compact('coach_id', 'plans'));
    }

    public function upgradePlanSubmit(Request $request)
    {
        // dd($request->all());
        try {

            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user = User::find($request->user_id);

            $plan = Subscription::where('is_deleted', 0)
                ->where('id', $request->plan_id)
                ->where('is_active', 1)
                ->first();
// dd($plan);
            if (!$plan) {
                return redirect()->back()->with('error', 'Plan not found.');
            }

            $startDate = Carbon::now();

            if ($plan->duration_unit == 1) {
                $duration_unit = 'Daily';
                $endDate = $startDate->copy()->addDays($plan->plan_duration);
            } elseif ($plan->duration_unit == 2) {
                $duration_unit = 'Monthly';
                $endDate = $startDate->copy()->addMonths($plan->plan_duration);
            } elseif ($plan->duration_unit == 3) {
                $duration_unit = 'Yearly';
                $endDate = $startDate->copy()->addYears($plan->plan_duration);
            }

            UserSubscription::where('user_id', $user->id)
                ->update(['is_active' => 0]);

            $subscription = UserSubscription::create([
                'user_id'        => $user->id,
                'coach_name'     => $user->first_name . ' ' . $user->last_name,
                'plan_id'        => $plan->id,
                'amount'         => $plan->plan_amount,
                'plan_name'      => $plan->plan_name,
                'duration_unit'  => $duration_unit,
                'plan_content'   => $plan->plan_content,
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'txn_id'         => 'ADMIN-MANUAL-' . time(),
                'payment_method' => 'admin_manual',
                'payment_type'   => 'none',
                'payment_last4'  => null,
                'is_active'      => 1,
            ]);

            $pdf = \PDF::loadView('pdf.coach_payment_history', [
                'paymentHistory' => $subscription
            ]);

            $folderPath = public_path('pdf/coach_payment_history');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $fileName = 'coach_payment_history_' . $subscription->id . '.pdf';
            $pdfPath = $folderPath . '/' . $fileName;

            file_put_contents($pdfPath, $pdf->output());

            $pdfUrl = asset('pdf/coach_payment_history/' . $fileName);

            return redirect()->route('admin.coachList')->with('success', 'Plan upgraded successfully!');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ActiveCoaching()
    {
        try {
            $bookings = BookingPackages::with(['user', 'coachPackage'])
                ->whereHas('user', function ($query) {
                    $query->where('user_type', 2)
                        ->where('email_verified', 1)
                        ->where('user_status', 1)
                        ->where('is_deleted', 0);
                })
                ->whereHas('coachPackage', function ($query) {
                    $query->where('package_status', 1)
                        ->where('is_deleted', 0);
                })
                ->orderBy('session_date_start', 'desc')
                ->paginate(20);

            return view('admin.activeCoaching', compact('bookings'));

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

        public function getContactMessage()
    {
        $getContactMessage = DB::table('contact_messages')->orderBy('id', 'DESC')->paginate(20);
        return view('admin.getContactMessage', compact('getContactMessage'));
    }

        public function DeleteContactMessage(Request $request)
    {
        if(!$request->ids){
            return back()->with('error', 'Please select at least one message.');
        }

        DB::table('contact_messages')->whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected messages deleted successfully.');
    }

     public function coachChats()
    {
        $data['chatList'] = DB::table('admin_coach_chats as chat')
            ->join('users', 'users.id', '=', 'chat.sender_id')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.profile_image',
                DB::raw('COUNT(CASE WHEN chat.is_read = 0 AND chat.sender_type = "coach" THEN 1 END) as unread_count'),
                DB::raw('(SELECT MAX(created_at) FROM admin_coach_chats WHERE (sender_id = users.id OR receiver_id = users.id)) as last_message_time')
            )
            ->where('chat.sender_type', 'coach')
            ->groupBy('chat.sender_id', 'users.id', 'users.first_name', 'users.last_name', 'users.profile_image')
            ->orderBy('last_message_time', 'desc')
            ->get();

            // dd( $data['chatList']);
            return view('admin.coachChats')->with($data);
    }

     public function loadMessages(Request $request)
        {
           $admin = DB::table('users')->where('user_type',1)->first();
            $user_id = $admin->id;
            $user_type = 'admin';

            $receiver_id = $request->receiver_id;

    
            $other_user_type = 'coach';

            if ($request->has('mark_read') && $request->mark_read == 1) {
                AdminCoachChat::where('sender_id', $receiver_id)
                    ->where('receiver_id', $user_id)
                    ->where('receiver_type', $user_type)
                    ->where('sender_type', $other_user_type)
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);
            }

                $messages = AdminCoachChat::where(function ($q) use ($user_id, $receiver_id, $user_type, $other_user_type) {
                    $q->where('sender_id', $user_id)
                        ->where('receiver_id', $receiver_id)
                        ->where('sender_type', $user_type)
                        ->where('receiver_type', $other_user_type);
                })->orWhere(function ($q) use ($user_id, $receiver_id, $user_type, $other_user_type) {
                    $q->where('sender_id', $receiver_id)
                        ->where('receiver_id', $user_id)
                        ->where('sender_type', $other_user_type)
                        ->where('receiver_type', $user_type);
                })->orderBy('created_at')->get();

            return view('admin.messages', compact('messages', 'user_id','receiver_id'))->render();
        }

    public function sendMessage(Request $request)
    {
        $admin = DB::table('users')->where('user_type',1)->first();
        $receiver = User::find($request->receiver_id);

        if (!$receiver) {
            return response()->json(['error' => 'Receiver not found'], 404);
        }

        $message = AdminCoachChat::create([
            'sender_id'     => $admin->id,
            'sender_type'   => 'admin',
            'receiver_id'   => $receiver->id,
            'receiver_type' => 'coach',   // FIXED
            'message'       => $request->message ?? '',
            'is_read'       => 0,
        ]);

        broadcast(new AdminMessageSent($message))->toOthers();

        return response()->json(['status' => 'Message sent!']);
    }










   
}
