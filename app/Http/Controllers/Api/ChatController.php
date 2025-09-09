<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    // send message (user ↔ coach)
    public function sendMessage(Request $request)
    {
        // echo "test";die;
        // $id = Auth::id();
        // echo $id;die;
        try{
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

    //  echo "test";die;
    //  $id = Auth::id();
    //  echo $id;die;
    $receiver_id = $request->receiver_id;
    $user_id = Auth::id();

    try {
     
        Message::where('sender_id', $receiver_id)
            ->where('receiver_id', $user_id)
            ->where('is_read', 0) 
            ->update([
                'is_read' => 1,
            ]);

     
        $messages = Message::where(function ($q) use ($receiver_id, $user_id) {
                $q->where('sender_id', $user_id)
                  ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($q) use ($receiver_id, $user_id) {
                $q->where('sender_id', $receiver_id)
                  ->where('receiver_id', $user_id);
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



    // public function generalCoachChatList(Request $request)
    // {
    //     $user_id = Auth::id(); 

    //     try {
    //         $name = $request->name ?? null;

    //         if (Auth::user()->user_type == 2) {
    //             $users = User::where('user_type', 3) 
    //                 ->where('email_verified', 1)
    //                 ->where('user_status', 1)
    //                 ->where('is_deleted', 0)
    //                 ->where('is_verified', 1)
    //                 ->when(!empty($name), function ($query) use ($name) {
    //                     $parts = explode(' ', $name);
    //                     if (count($parts) >= 2) {
    //                         $query->where('first_name', 'LIKE', '%' . $parts[0] . '%')
    //                             ->where('last_name', 'LIKE', '%' . $parts[1] . '%');
    //                     } else {
    //                         $query->where(function ($q) use ($name) {
    //                             $q->where('first_name', 'LIKE', '%' . $name . '%')
    //                             ->orWhere('last_name', 'LIKE', '%' . $name . '%');
    //                         });
    //                     }
    //                 })
    //                 ->whereHas('messages', function ($query) use ($user_id) {
    //                     $query->where(function ($q) use ($user_id) {
    //                         $q->where('sender_id', $user_id)
    //                         ->orWhere('receiver_id', $user_id);
    //                     })
    //                     ->where('message_type', 1);  
    //                 })
    //                 ->get()
    //                 ->map(function($coach) {
    //                 return [
    //                     'id' => $coach->id,
    //                     'name' => $coach->first_name . ' ' . $coach->last_name,
    //                     'last_message' => $coach->messages->where('message_type', 1)->last()->message ?? '',  // Last message of type 1
    //                     'last_message_time' => optional($coach->messages->where('message_type', 1)->last())->created_at?->format('H:i'),
    //                     'unread_count' => $coach->messages->where('message_type', 1)->where('is_read', 0)->count(),  // Count unread messages of type 1
    //                 ];
    //             });

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Coach Chat List for User',
    //                 'data'    => $users
    //             ]);
    //         }

    //         if (Auth::user()->user_type == 3) {
    //             $users = User::where('user_type', 2) 
    //                 ->where('email_verified', 1)
    //                 ->where('user_status', 1)
    //                 ->where('is_deleted', 0)
    //                 ->where('is_verified', 1)
    //                 ->when(!empty($name), function ($query) use ($name) {
    //                     $parts = explode(' ', $name);
    //                     if (count($parts) >= 2) {
    //                         $query->where('first_name', 'LIKE', '%' . $parts[0] . '%')
    //                             ->where('last_name', 'LIKE', '%' . $parts[1] . '%');
    //                     } else {
    //                         $query->where(function ($q) use ($name) {
    //                             $q->where('first_name', 'LIKE', '%' . $name . '%')
    //                             ->orWhere('last_name', 'LIKE', '%' . $name . '%');
    //                         });
    //                     }
    //                 })
    //                 ->whereHas('messages', function ($query) use ($user_id) {
    //                     $query->where(function ($q) use ($user_id) {
    //                         $q->where('sender_id', $user_id)
    //                         ->orWhere('receiver_id', $user_id);
    //                     })
    //                     ->where('message_type', 1);  
    //                 })
    //                 ->get()
    //         ->map(function($coach) {
    //                 return [
    //                     'id' => $coach->id,
    //                     'name' => $coach->first_name . ' ' . $coach->last_name,
    //                     'last_message' => $coach->messages->where('message_type', 1)->last()->message ?? '',  // Last message of type 1
    //                     'last_message_time' => optional($coach->messages->where('message_type', 1)->last())->created_at?->format('H:i'),
    //                     'unread_count' => $coach->messages->where('message_type', 1)->where('is_read', 0)->count(),  // Count unread messages of type 1
    //                 ];
    //             });

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'User Chat List for Coach',
    //                 'data'    => $users
    //             ]);
    //         }

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong while fetching data.',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }

public function generalCoachChatList(Request $request)
{
    $user_id = Auth::id(); 
    $name = $request->name ?? null;
    $message_type = $request->message_type ?? 1; 

    try {
        if (Auth::user()->user_type == 2) {
            $users = User::where('user_type', 3) 
                ->where('email_verified', 1)
                ->where('user_status', 1)
                ->where('is_deleted', 0)
                ->where('is_verified', 1)
                ->when(!empty($name), function ($query) use ($name) {
                    $parts = explode(' ', $name);
                    if (count($parts) >= 2) {
                        $query->where('first_name', 'LIKE', '%' . $parts[0] . '%')
                              ->where('last_name', 'LIKE', '%' . $parts[1] . '%');
                    } else {
                        $query->where(function ($q) use ($name) {
                            $q->where('first_name', 'LIKE', '%' . $name . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                        });
                    }
                })
                ->whereHas('messages', function ($query) use ($user_id, $message_type) {
                    $query->where(function ($q) use ($user_id) {
                        $q->where('sender_id', $user_id)
                          ->orWhere('receiver_id', $user_id);
                    })
                    ->where('message_type', $message_type); // Use dynamic message type
                })
                ->get()
                ->map(function($coach) use ($message_type) {
                    return [
                        'id' => $coach->id,
                        'name' => $coach->first_name . ' ' . $coach->last_name,
                        'last_message' => $coach->messages->where('message_type', $message_type)->last()->message ?? '',
                        'last_message_time' => optional($coach->messages->where('message_type', $message_type)->last())->created_at?->format('H:i'),
                        'unread_count' => $coach->messages->where('message_type', $message_type)->where('is_read', 0)->count(),
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Coach Chat List for User',
                'data'    => $users
            ]);
        }

        if (Auth::user()->user_type == 3) {
            $users = User::where('user_type', 2) 
                ->where('email_verified', 1)
                ->where('user_status', 1)
                ->where('is_deleted', 0)
                ->where('is_verified', 1)
                ->when(!empty($name), function ($query) use ($name) {
                    $parts = explode(' ', $name);
                    if (count($parts) >= 2) {
                        $query->where('first_name', 'LIKE', '%' . $parts[0] . '%')
                              ->where('last_name', 'LIKE', '%' . $parts[1] . '%');
                    } else {
                        $query->where(function ($q) use ($name) {
                            $q->where('first_name', 'LIKE', '%' . $name . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                        });
                    }
                })
                ->whereHas('messages', function ($query) use ($user_id, $message_type) {
                    $query->where(function ($q) use ($user_id) {
                        $q->where('sender_id', $user_id)
                          ->orWhere('receiver_id', $user_id);
                    })
                    ->where('message_type', $message_type); // Use dynamic message type
                })
                ->get()
                ->map(function($coach) use ($message_type) {
                    return [
                        'id' => $coach->id,
                        'name' => $coach->first_name . ' ' . $coach->last_name,
                        'last_message' => $coach->messages->where('message_type', $message_type)->last()->message ?? '',
                        'last_message_time' => optional($coach->messages->where('message_type', $message_type)->last())->created_at?->format('H:i'),
                        'unread_count' => $coach->messages->where('message_type', $message_type)->where('is_read', 0)->count(),
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'User Chat List for Coach',
                'data'    => $users
            ]);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while fetching data.',
            'error'   => $e->getMessage()
        ], 500);
    }
}





}