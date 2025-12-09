<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachReview extends Model
{
      use HasFactory;

    protected $table = 'coach_reviews';

    protected $fillable = [
        'title',
        'description',
        'rating',
        'coach_id',
        'status',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
