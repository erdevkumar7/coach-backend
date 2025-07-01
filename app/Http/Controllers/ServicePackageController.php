<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserService;
use Illuminate\Http\Request;
use DB;

class ServicePackageController extends Controller
{
    public function servicePackageList($id)
    {
        $packages = DB::table('user_service_packages')
            ->where('coach_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
 
        return view('admin.service_package_list', [
            'packages' => $packages,
            'coach_id' => $id,
        ]);
    }


    public function addServicePackage(Request $request, $id, $package_id = null)
    {
        $service   = DB::table('master_service')->where('is_active', 1)->get();
        $type      = DB::table('coach_type')->where('is_active', 1)->get();
        $category  = DB::table('coaching_cat')->where('is_active', 1)->get();
        $mode      = DB::table('delivery_mode')->where('is_active', 1)->get();
        $age_groups = DB::table('age_group')->select('id', 'group_name', 'age_range')->where('is_active', 1)->get();
        $cancellation_policies = DB::table('master_cancellation_policy')->where('is_active', 1)->get();

        $user_detail = DB::table('users')->where('id', $id)->first();
        $profession  = DB::table('user_professional')->where('user_id', $id)->first();
        $subtype     = DB::table('coach_subtype')->where('coach_type_id', $profession->coach_type ?? 0)->get();

        $selectedServiceIds = UserService::where('user_id', $id)->pluck('service_id')->toArray();

        $package = null;
        if ($package_id) {
            $package = DB::table('user_service_packages')
                ->where('id', $package_id)
                ->where('coach_id', $id)
                ->first();
        }

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'title'                => 'required|string|max:255',
                'short_description'    => 'nullable|string|max:200',
                'coaching_category'    => 'nullable|string',
                'description'          => 'nullable|string',
                'focus'                => 'nullable|string',
                'coaching_type'        => 'nullable|integer',
                'delivery_mode'        => 'nullable|string',
                'session_count'        => 'nullable|integer',
                'session_duration'     => 'nullable|string',
                'age_group'      => 'nullable|string',
                'price'                => 'nullable|numeric|min:0',
                'currency'             => 'nullable|string|max:3',
                'booking_slot'         => 'nullable|date',
                'booking_window'       => 'nullable|string|max:100',
                'cancellation_policy'  => 'nullable|in:flexible,moderate,strict',
                'rescheduling_policy'  => 'nullable|string',
                'media_file'           => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf|max:2048',
                'status'               => 'nullable|in:draft,published',
            ]);

            // Handle media file upload
            $mediaFile = null;
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $mediaFile = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/service_packages'), $mediaFile);
            }

            $data = [
                'coach_id'            => $id,
                'title'               => $request->title,
                'short_description'   => $request->short_description,
                'coaching_category'   => $request->coaching_category,
                'description'         => $request->description,
                'focus'               => $request->focus,
                'coaching_type'       => $request->coaching_type,
                'delivery_mode'       => $request->delivery_mode,
                'session_count'       => $request->session_count,
                'session_duration'    => $request->session_duration,
                'age_group'     => $request->age_group,
                'price'               => $request->price,
                'currency'            => $request->currency,
                'booking_slot'        => $request->booking_slot,
                'booking_window'      => $request->booking_window,
                'cancellation_policy' => $request->cancellation_policy,
                'rescheduling_policy' => $request->rescheduling_policy,
                'media_file'          => $mediaFile ?? ($package->media_file ?? null),
                'status'              => $request->status,
                'updated_at'          => now(),
            ];

            if ($package_id) {
                DB::table('user_service_packages')->where('id', $package_id)->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('user_service_packages')->insert($data);
            }

            return redirect()->route("admin.servicePackageList", $id)->with("success", "Service Package saved successfully.");
        }

        return view('admin.service_package_form', compact(
            'id',
            'user_detail',
            'category',
            'type',
            'subtype',
            'mode',
            'service',
            'selectedServiceIds',
            'package',
            'age_groups',
            'cancellation_policies'
        ));
    }
}
