<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\CoachingRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ChatController extends Controller
{
    // send message (user ↔ coach)
    public function sendMessage(Request $request)
    {
        // echo "test";die;
        // $id = Auth::id();
        // echo $id;die;
        try {
            $request->validate([
                'receiver_id' => 'required|integer',
                'message'     => 'required|string'
            ]);

            $message = Message::create([
                'sender_id'   => Auth::id(),
                'receiver_id' => $request->receiver_id,
                'message'     => $request->message,
                'message_type'     => $request->message_type,
            ]);

            broadcast(new MessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data'    => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function getMessages(Request $request)
    {


        try {




            //  $id = Auth::id();
            //  echo $id;die;
            $receiver_id = $request->receiver_id;
            $message_type = $request->message_type;
            $user_id = Auth::id();



            $validator = Validator::make($request->all(), [
                'receiver_id'      => 'required|integer',
                'message_type'       => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }


            Message::where('sender_id', $receiver_id)
                ->where('receiver_id', $user_id)
                ->where('message_type', $message_type)
                ->where('is_read', 0)
                ->update([
                    'is_read' => 1,
                ]);

            $messages = Message::with([
                'sender:id,first_name,last_name',
                'receiver:id,first_name,last_name'
            ])
                ->where(function ($q) use ($receiver_id, $user_id, $message_type) {
                    // Messages sent by the logged-in user
                    $q->where('sender_id', $user_id)
                        ->where('receiver_id', $receiver_id)
                        ->where('message_type', $message_type);
                })
                ->orWhere(function ($q) use ($receiver_id, $user_id, $message_type) {
                    // Messages received by the logged-in user
                    $q->where('sender_id', $receiver_id)
                        ->where('receiver_id', $user_id)
                        ->where('message_type', $message_type);
                })
                ->orderBy('created_at', 'asc')
                ->get();


            return response()->json([
                'success' => true,
                'message' => 'Messages retrieved successfully',
                'data'    => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function generalCoachChatList(Request $request)
    {
        $user_id = Auth::id();
        $name = $request->name ?? null;
        $message_type = $request->message_type;

        try {
            $user = Auth::user();

            if ($user->user_type == 2) {
                $query = User::where('users.user_type', 3)
                    ->where('users.email_verified', 1)
                    ->where('users.user_status', 1)
                    ->where('users.is_deleted', 0)
                    ->where('users.is_verified', 1);

                if ($message_type == 1) {
                } elseif ($message_type == 2) {
                    $query->whereHas('CoachRequest', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                } elseif ($message_type == 3) {
                    $query->whereHas('CoachBookingPackages', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Invaid message type',
                    ]);
                }

                if (!empty($name)) {
                    $query->when($name, function ($q) use ($name) {
                        $parts = explode(' ', $name);
                        if (count($parts) >= 2) {
                            $q->where('users.first_name', 'LIKE', '%' . $parts[0] . '%')
                                ->where('users.last_name', 'LIKE', '%' . $parts[1] . '%');
                        } else {
                            $q->where(function ($qq) use ($name) {
                                $qq->where('users.first_name', 'LIKE', '%' . $name . '%')
                                    ->orWhere('users.last_name', 'LIKE', '%' . $name . '%');
                            });
                        }
                    });
                }

                $users = $query->select('users.*')
                    ->selectRaw('(SELECT message FROM messages
                                    WHERE ((sender_id = users.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = users.id))
                                    AND message_type = ?
                                    ORDER BY created_at DESC LIMIT 1) as last_message', [$user_id, $user_id, $message_type])
                    ->selectRaw('(SELECT created_at FROM messages
                                    WHERE ((sender_id = users.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = users.id))
                                    AND message_type = ?
                                    ORDER BY created_at DESC LIMIT 1) as last_message_time', [$user_id, $user_id, $message_type])
                    ->selectRaw('(SELECT COUNT(*) FROM messages
                                    WHERE sender_id = users.id
                                    AND receiver_id = ?
                                    AND is_read = 0
                                    AND message_type = ?) as unread_count', [$user_id, $message_type])
                    ->get()
                    ->map(function ($coach) {
                        return [
                            'id' => $coach->id,
                            'name' => $coach->first_name . ' ' . $coach->last_name,
                            'profile_image' => $coach->profile_image ? asset('public/uploads/profile_image/' . $coach->profile_image) : null,
                            'last_message' => $coach->last_message ?? '',
                            'last_message_time' => $coach->last_message_time ? \Carbon\Carbon::parse($coach->last_message_time)->format('H:i') : null,
                            'unread_count' => $coach->unread_count,
                        ];
                    });

                return response()->json([
                    'success' => true,
                    'message' => 'Coach Chat List for User',
                    'data' => $users,
                ]);
            }

            if ($user->user_type == 3) {
                $query = User::where('users.user_type', 2)
                    ->where('users.email_verified', 1)
                    ->where('users.user_status', 1)
                    ->where('users.is_deleted', 0)
                    ->where('users.is_verified', 1);

                if ($message_type == 1) {
                } elseif ($message_type == 2) {
                    $query->whereHas('UserRequest', function ($q) use ($user_id) {
                        $q->where('coach_id', $user_id);
                    });
                } elseif ($message_type == 3) {
                    $query->whereHas('UserBookingPackages', function ($q) use ($user_id) {
                        $q->where('coach_id', $user_id);
                    });
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Invaid message type',
                    ]);
                }

                if (!empty($name)) {
                    $query->when($name, function ($q) use ($name) {
                        $parts = explode(' ', $name);
                        if (count($parts) >= 2) {
                            $q->where('users.first_name', 'LIKE', '%' . $parts[0] . '%')
                                ->where('users.last_name', 'LIKE', '%' . $parts[1] . '%');
                        } else {
                            $q->where(function ($qq) use ($name) {
                                $qq->where('users.first_name', 'LIKE', '%' . $name . '%')
                                    ->orWhere('users.last_name', 'LIKE', '%' . $name . '%');
                            });
                        }
                    });
                }

                $users = $query->select('users.*')
                    ->selectRaw('(SELECT message FROM messages
                                    WHERE ((sender_id = users.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = users.id))
                                    AND message_type = ?
                                    ORDER BY created_at DESC LIMIT 1) as last_message', [$user_id, $user_id, $message_type])
                    ->selectRaw('(SELECT created_at FROM messages
                                    WHERE ((sender_id = users.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = users.id))
                                    AND message_type = ?
                                    ORDER BY created_at DESC LIMIT 1) as last_message_time', [$user_id, $user_id, $message_type])
                    ->selectRaw('(SELECT COUNT(*) FROM messages
                                    WHERE sender_id = users.id
                                    AND receiver_id = ?
                                    AND is_read = 0
                                    AND message_type = ?) as unread_count', [$user_id, $message_type])
                    ->get()
                    ->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'name' => $student->first_name . ' ' . $student->last_name,
                            'profile_image' => $student->profile_image ? asset('public/uploads/profile_image/' . $student->profile_image) : null,
                            'last_message' => $student->last_message ?? '',
                            'last_message_time' => $student->last_message_time ? \Carbon\Carbon::parse($student->last_message_time)->format('H:i') : null,
                            'unread_count' => $student->unread_count,
                        ];
                    });

                return response()->json([
                    'success' => true,
                    'message' => 'User Chat List for Coach',
                    'data' => $users,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid user type.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
