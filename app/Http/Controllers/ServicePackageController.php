<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserService;
use App\Models\UserServicePackage;
use Illuminate\Http\Request;
use DB;

class ServicePackageController extends Controller
{
    public function servicePackageList($id)
    {
        $user_detail = DB::table('users')->where('id', $id)->first();
        if (!$user_detail) {
            return back()->with('error', 'User not found.');
        }
        // $packages = DB::table('user_service_packages')->where('coach_id', $id)->orderBy('created_at', 'desc')->get();
        $packages = UserServicePackage::with(['deliveryMode', 'sessionFormat'])
            ->where('coach_id', $id)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.service_package_list', [
            'packages' => $packages,
            'coach_id' => $id,
            'user_detail' => $user_detail,
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
        $session_formats = DB::table('master_session_format')->where('is_active', 1)->get();
        $price_models = DB::table('master_price_model')->where('is_active', 1)->get();

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
                'short_description'    => 'nullable|string',
                'coaching_category'    => 'nullable|string',
                'description'          => 'nullable|string',
                'focus'                => 'nullable|string',
                'coaching_type'        => 'nullable|string',
                'delivery_mode'        => 'nullable|string',
                'session_count'        => 'nullable|string',
                'session_duration'     => 'nullable|string',
                'age_group'            => 'nullable|string',
                'price'                => 'nullable|string',
                'currency'             => 'nullable|string',
                'booking_slot'         => 'nullable',
                'booking_window'       => 'nullable|string',
                'cancellation_policy'  => 'nullable',
                'rescheduling_policy'  => 'nullable|string',
                'media_file'           => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf|max:5096',
                'status'               => 'nullable',
            ]);

            // Handle media file upload
            $mediaFile = null;
            $originalFilename = null;
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $originalFilename = $file->getClientOriginalName();
                $mediaFile = time() . '_' . $originalFilename;

                // Step 1: Delete the old file if it exists
                if ($request->media_file_name) {
                    $oldPath = public_path('uploads/service_packages/' . $request->media_file_name);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Step 2: Move the new file
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
                'session_format'      => $request->session_format,
                'age_group'           => $request->age_group,
                'price'               => $request->price,
                'price_model'         => $request->price_model,
                'currency'            => $request->currency,
                'booking_slots'        => $request->booking_slots,
                'booking_availability' => $request->booking_availability,
                'booking_window'      => $request->booking_window,
                'cancellation_policy' => $request->cancellation_policy,
                'rescheduling_policy' => $request->rescheduling_policy,
                'media_file'          => $mediaFile ?? ($package->media_file ?? null),
                'media_original_name' => $originalFilename ?? ($package->media_original_name ?? null),
                'status'              => $request->status,
                'booking_slots'       => $request->booking_slots,
                'updated_at'          => now(),
            ];

            if ($request->service_package_id) {
                DB::table('user_service_packages')->where('id', $request->service_package_id)->update($data);
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
            'cancellation_policies',
            'session_formats',
            'price_models'
        ));
    }

    public function updatePackageStatus(Request $request)
    {
        $package = UserServicePackage::find($request->package_id);
        $package->package_status = $request->status;
        $package->save();
    }

    public function deleteServicePackage(Request $request)
    {
        $package = UserServicePackage::find($request->package_id);
        $package->is_deleted = 1;
        $package->save();
    }
}