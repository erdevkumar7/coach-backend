<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HomeSetting;
use App\Models\Contact;
use App\Models\AboutSetting;
use App\Models\TeamMember;


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
                'title' => 'required|string|max:255',
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


   
}
