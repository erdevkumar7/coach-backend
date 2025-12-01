<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    // only allow authenticated users
    return (int) $user->id === (int) $receiverId || true;
});

// Add presence channel for online status
Broadcast::channel('presence-online', function ($user) {
    if ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'user_type' => $user->user_type,
            'profile_image' => $user->profile_image
        ];
    }
});

// Broadcast::channel('adminchat.{user1}_{user2}', function ($user, $user1, $user2) {
//     // Ensure user is part of this chat
//     return $user->id == $user1 || $user->id == $user2;
// });
Broadcast::channel('adminchat.{user1}_{user2}', function ($user, $user1, $user2) {
    return (int)$user->id == (int)$user1 || (int)$user->id == (int)$user2;
});