<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Events\MessageSent;

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
                // ->where('is_active', 1)
                ->find($request->coach_id);
            if (!$coach) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Coach',
                ], 422);
            }

                 $message =  Message::create([
                'sender_id'    => $user->id,
                'receiver_id'  => $coach->id,
                'message'   => $request->your_inquiry,
                'is_read' => 0,
                'message_type'  => 1,
            ]);
            
              broadcast(new MessageSent($message))->toOthers();


            // $coach_email = $coach->email;
            // $mailData = [
            //     'coach_name'    => $coach->first_name . ' ' . $coach->last_name,
            //     'user_name'     => $user->first_name . ' ' . $user->last_name,
            //     'user_email'    => $user->email,
            //     'user_contact'  => $user->contact_number,
            //     'subject'       => $request->subject ?? 'Inquiry from User',
            //     'message'       => $request->your_inquiry ?? 'No message provided.',
            // ];

            // Mail::send([], [], function ($message) use ($mailData, $coach_email) {
            //     $message->to($coach_email)
            //         ->subject($mailData['subject'])
            //         ->html(
            //             "Dear {$mailData['coach_name']},<br><br>" .
            //                 "You have received a new inquiry from a user:<br><br>" .
            //                 "<strong>Name:</strong> {$mailData['user_name']}<br>" .
            //                 "<strong>Email:</strong> {$mailData['user_email']}<br>" .
            //                 "<strong>Contact Number:</strong> {$mailData['user_contact']}<br>" .
            //                 "<strong>Message:</strong> {$mailData['message']}<br><br>" .
            //                 "Thanks,<br>Your Platform Team"
            //         );
            // });

            return response()->json([
                'status' => true,
                'message' => 'Message sent successfully.',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while sending the message.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        public function getNotifications()
    {
        try {
            $user = Auth::user();

          if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized or invalid user.'
            ], 401);
        }

            $notifications = Message::with('sender')
                  ->where('receiver_id', $user->id)
                 ->where('is_read', 0)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($msg) {
                    return [
                        'message_id'  => $msg->id,
                        'sender_id'   => $msg->sender_id,
                        'sender_user_type'  => $msg->sender->user_type ?? null,
                        'message'     => strip_tags($msg->message),
                        'is_read'     => $msg->is_read,
                        'message_type'=> $msg->message_type,
                        'time'        => $msg->created_at->diffForHumans(),
                        'sender_detail' => [
                        'id'         => $msg->sender->id ?? null,
                        'first_name' => $msg->sender->first_name ?? null,
                        'last_name' => $msg->sender->last_name ?? null,
                        'user_type'  => $msg->sender->user_type ?? null,
                        'profile'    => $msg->sender->profile_image ? asset('public/uploads/profile_image/' . $msg->sender->profile_image) : null,
                    ],
                    ];
                });

            return response()->json([
                'status' => true,
                'count'  => $notifications->count(),
                'notifications' => $notifications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error while fetching notifications.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function markNotificationAsRead(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized or invalid user.'
                ], 401);
            }

            // $validator = Validator::make($request->all(), [
            //     'message_id' => 'required|integer|exists:messages,id',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Validation failed',
            //         'errors' => $validator->errors()
            //     ], 422);
            // }

            $message = Message::where('id', $request->message_id)
                ->where('receiver_id', $user->id)
                ->first();

            if (!$message) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message not found or unauthorized.'
                ], 404);
            }

            $message->is_read = 1;
            $message->save();

            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error while marking notification as read.',
                'error' => $e->getMessage()
            ]);
        }
    }

        public function AllNotificationsRead()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized or invalid user.'
                ], 401);
            }

            Message::where('receiver_id', $user->id)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);

            return response()->json([
                'status' => true,
                'message' => 'All notifications marked as read.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error while marking notifications as read.',
                'error' => $e->getMessage()
            ]);
        }
    }


}
