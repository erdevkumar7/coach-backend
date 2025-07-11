<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteCoach extends Model
{
    protected $fillable = [
        'coach_id',
        'user_id'
    ];
    public $timestamps = false;
    protected $table = 'favorite_coach';
}
