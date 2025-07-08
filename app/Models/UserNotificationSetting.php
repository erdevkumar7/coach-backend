<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserNotificationSetting extends Model
{
    protected $table = 'user_notification_settings';

    protected $fillable = [
        'user_id',
        'new_coach_match_alert',
        'message_notifications',
        'booking_reminders',
        'platform_announcements',
        'blog_recommendations',
        'billing_updates'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
