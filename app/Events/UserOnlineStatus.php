<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnlineStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $isOnline;

    public function __construct($user, $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
        // Don't queue this event for real-time updates
        $this->dontBroadcastToCurrentUser();
    }

    public function broadcastOn()
    {
        return new PresenceChannel('presence-online');
    }

    public function broadcastAs()
    {
        return 'user.status';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'is_online' => $this->isOnline,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'user_type' => $this->user->user_type,
                'profile_image' => $this->user->profile_image
            ]
        ];
    }
}