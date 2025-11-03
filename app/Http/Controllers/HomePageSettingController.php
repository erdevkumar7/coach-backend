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





   
}
