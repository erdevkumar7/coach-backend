<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminCoachChat extends Model
{
       use HasFactory;
    protected $fillable = [
     'sender_id',
    'receiver_id', 
    'sender_type', 
    'receiver_type', 
    'message',
    'attachment',
    'is_read'
    ];
}
