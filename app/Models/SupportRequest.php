<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'user_type',
        'reason',
        'subject',
        'description',
        'screenshot',
        'agree_to_contact',
    ];
}
