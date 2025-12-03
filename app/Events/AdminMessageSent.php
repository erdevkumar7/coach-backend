<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AdminCoachChat;

class AdminMessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;

    public function __construct(AdminCoachChat $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
    //      \Log::info('Broadcast running', [
    //     'message_id' => $this->message->id
    // ]);
        $ids = [$this->message->sender_id, $this->message->receiver_id];
        sort($ids);
        return new PrivateChannel('adminchat.' . $ids[0] . '_' . $ids[1]);
    }

    public function broadcastAs()
    {
        return 'AdminMessageSent';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'receiver_id' => $this->message->receiver_id,
                'message' => $this->message->message,
                'created_at' => $this->message->created_at->toDateTimeString(),
            ]
        ];
    }
}
