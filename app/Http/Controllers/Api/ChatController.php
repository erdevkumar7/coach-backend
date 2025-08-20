<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
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

    // fetch chat history
    public function getMessages78(Request $request)
    {
        $receiver_id = $request->receiver_id;

         echo $receiver_id;die;
        // $id = Auth::id();
        // echo $id;die;

        try{

            $message = Message::where(function ($q) use ($receiver_id) {
                $q->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
            })->orWhere(function ($q) use ($receiver_id) {
                $q->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
            })->update([
                'is_read'=> 1 ,
            ]);

            $messages = Message::where(function ($q) use ($receiver_id) {
                $q->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
            })->orWhere(function ($q) use ($receiver_id) {
                $q->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Message received successfully',
                'data'    => $messages
            ]);

             // return response()->json($messages);
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

}