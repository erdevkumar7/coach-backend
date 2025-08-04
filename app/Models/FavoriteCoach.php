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



    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function coachSubtypeUser()
    {
        return $this->hasOne(CoachSubtypeUser::class, 'user_id', 'coach_id');
    }

}