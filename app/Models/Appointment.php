<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Appointment extends Model
{
     protected $fillable = [
        'start_time',
        'finish_time',
        'duration_minutes',
        'comments',
        'user_id',
        'coach_id',
        'status',
        'location',
        'meeting_link',
        'rescheduled_at',
        'rescheduled_by',
        'created_by',
    ];
    protected $dates = [
        'start_time',
        'finish_time',
        'rescheduled_at',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rescheduledBy()
    {
        return $this->belongsTo(User::class, 'rescheduled_by');
    }


}
