<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HomeSetting;


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
        
            $section->subtitle = null;
            $section->description = null;
        }

        if ($type === 'top') {
            $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string',
            ]);

            $section->title = $request->title;
            $section->subtitle = $request->subtitle;
        }

        $section->section_name = $type;
        $section->save();

        return redirect()->route('admin.manage', $type)
            ->with('success', ucfirst(str_replace('_', ' ', $type)) . ' section updated successfully!');
    }

   
}
