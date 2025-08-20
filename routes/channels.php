<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    // only allow authenticated users
    return (int) $user->id === (int) $receiverId || true;
});