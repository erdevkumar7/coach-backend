<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Coach;

class Review extends Model
{
    protected $table = 'review';

    // public function coach()
    // {
    //     return $this->belongsTo(Coach::class, 'coach_id');
    // }

    protected $fillable = [
        'user_id',
        'coach_id',
        'review_text',
        'rating',
        'status',
        'user_status',
        'reply_id',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
