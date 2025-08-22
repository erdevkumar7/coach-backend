<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CoachingRequest extends Model
{

    protected $table = 'coaching_request';

    protected $fillable = [
        'request_id',
        'user_id',
        'coach_id',
        'looking_for',
        'coaching_category',
        'preferred_mode_of_delivery',
        'location',
        'coaching_goal',
        'language_preference',
        'preferred_communication_channel',
        'learner_age_group',
        'preferred_teaching_style',
        'budget_range',
        'preferred_schedule',
        'coach_gender',
        'coach_experience_level',
        'only_certified_coach',
        'preferred_start_date_urgency',
        'special_requirements',
        'is_active',
        'share_with_coaches',
        'coach_subtype',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function country()
    {
        return $this->belongTo(MasterCountry::class,'user_id');
    }
    // public function reviews()
    // {
    //     return $this->hasMany(Review::class, 'coach_id');
    // }

       public function coachingCategory()
    {
        return $this->hasOne(CoachType::class,'id','coaching_category');
    }

       public function coachingSubCategory()
    {
        return $this->hasOne(CoachSubType::class,'id','coach_subtype');
    }

       public function delivery_mode()
    {
        return $this->hasOne(DeliveryMode::class,'id','preferred_mode_of_delivery');
    }

}