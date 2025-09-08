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

    public function generalCoachChatList(Request $request)
{
    //  echo "test";die;
    $receiver_id = $request->receiver_id;
    $user_id = Auth::id();

    try {
     
    $name = $request->name ?? null;

    $coach_detail = User::with([
            'lastMessage', 
            'unreadMessages' => function($q) {
                $q->select('id', 'receiver_id'); 
            }
        ])
      
        ->where('user_type', 3)
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
        ->get()
        ->map(function ($coach) {
            return [
                'id' => $coach->id,
                'name' => $coach->first_name . ' ' . $coach->last_name,
                'last_message' => $coach->lastMessage?->message ?? '',
                'last_message_time' => optional($coach->lastMessage)->created_at?->format('H:i'),
                'unread_count' => $coach->unreadMessages->count(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'General Coach List',
            'data'    => $coach_detail
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while fetching data.',
            'error'   => $e->getMessage()
        ], 500);
    }
}

}