<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SupportRequest;

class SupportRequestController extends Controller
{
    public function AddSupportRequest(Request $request)
    {
        try{
            //return "Controller code working";
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'user_type' => 'required|in:1,2,3',
                'reason' => 'nullable|string|max:255',
                'subject' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'screenshot' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'agree_to_contact' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('screenshot')) {
                $img = $request->file('screenshot');
                $ext = $img->getClientOriginalExtension();
                $imgName = time() . '.' . $ext;
                $img->move(public_path('uploads/support_request'), $imgName);
            } else {
                return response()->json(['error' => 'Screenshot is not uploaded.'], 400);
            }


            $package = SupportRequest::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_type' => $request->user_type,
                'reason' => $request->reason,
                'subject' => $request->subject,
                'description' => $request->description,
                'screenshot' => $imgName,
                'agree_to_contact' => $request->agree_to_contact ?? false,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Support request added successfully',
                'data' => $package
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
