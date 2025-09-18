<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = [
    'user_id',
    'new_coach_match_alert',
    'message_notifications',
    'booking_reminders',
    'coaching_request_status',
    'platform_announcements',
    'blog_article_recommendations',
    'billing_updates',
    'communication_preference',
    'profile_visibility',
    'allow_ai_matching',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
