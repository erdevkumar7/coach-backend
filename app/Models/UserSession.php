<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token',
        'login_time',
        'logout_time',
        'last_active_at',
        'device',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
