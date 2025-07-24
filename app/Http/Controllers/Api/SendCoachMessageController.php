<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class SendCoachMessageController extends Controller
{



    public function coachSendMessage(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'coach_id'      => 'required|integer',
                'subject'       => 'required|string|max:255',
                'your_inquiry'  => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $coach = User::where('user_type', 3)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->find($request->coach_id);
            if (!$coach) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Coach',
                ], 422);
            }

            $coach_email = $coach->email;

            $mailData = [
                'coach_name'    => $coach->first_name . ' ' . $coach->last_name,
                'user_name'     => $user->first_name . ' ' . $user->last_name,
                'user_email'    => $user->email,
                'user_contact'  => $user->contact_number,
                'subject'       => $request->subject ?? 'Inquiry from User',
                'message'       => $request->your_inquiry ?? 'No message provided.',
            ];

            Mail::send([], [], function ($message) use ($mailData, $coach_email) {
                $message->to($coach_email)
                    ->subject($mailData['subject'])
                    ->html(
                        "Dear {$mailData['coach_name']},<br><br>" .
                        "You have received a new inquiry from a user:<br><br>" .
                        "<strong>Name:</strong> {$mailData['user_name']}<br>" .
                        "<strong>Email:</strong> {$mailData['user_email']}<br>" .
                        "<strong>Contact Number:</strong> {$mailData['user_contact']}<br>" .
                        "<strong>Message:</strong> {$mailData['message']}<br><br>" .
                        "Thanks,<br>Your Platform Team"
                    );
            });

            return response()->json([
                'status' => true,
                'message' => 'Message sent successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while sending the message.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
