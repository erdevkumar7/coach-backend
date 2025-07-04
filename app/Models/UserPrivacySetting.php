<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserPrivacySetting extends Model
{
    protected $table = 'users_privacy_settings';
    
    protected $fillable = [
        'user_id',
        'profile_visibility',
        'communication_email',
        'communication_in_app',
        'communication_push',
        'ai_personalization_agreed',
        'essential_cookies',
        'performance_cookies',
        'functional_cookies',
        'marketing_cookies',
        'accepted_all_cookies',
        'rejected_all_cookies',
        'customized_cookie_settings',
        ];


      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
