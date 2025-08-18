<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'user_type',
        'user_id',
        'reason',
        'subject',
        'description',
        'screenshot',
        'agree_to_contact',
    ];
}