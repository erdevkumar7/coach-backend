<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:20',
            'subject' => 'required|string|max:100',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = ContactMessage::create($request->only([
            'first_name', 'last_name', 'email', 'country_code',
            'phone_number', 'subject', 'message'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Message sent successfully',
            'data' => $contact
        ], 201);
    }
}