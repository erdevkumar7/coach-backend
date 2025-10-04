<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $table = 'user_professional';
     protected $fillable = [
        'user_id',
        'experience',
        'coaching_category',
        'delivery_mode',
        'price',
        'price_range',
        'age_group',
        'coach_type',
        'free_trial_session',
        'is_pro_bono',
       'experience',
       'budget_range',
        'video_link',
        'coach_subtype',
        'communication_channel',
        'budget_range',
        'is_volunteered_coach',
        'volunteer_coaching',
        'website_link',
        'youtube_link',
        'podcast_link',
        'blog_article',
        'insta_link',
        'fb_link',
        'linkdin_link',
        'booking_link',
        'objective',
    ];

    public function coachType()
    {
        return $this->belongsTo(CoachType::class, 'coach_type');
    }

    public function coachSubtype()
    {
        return $this->belongsTo(CoachSubType::class, 'coach_subtype');
    }

    public function deliveryMode()
    {
        return $this->belongsTo(DeliveryMode::class, 'delivery_mode');
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class, 'age_group');
    }

     public function priceRange()
    {
        return $this->belongsTo(MasterBudgetRange::class, 'price_range');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function languages()
    // {
    //     return $this->belongsToMany(Language::class, 'user_language', 'user_id', 'language_id');
    // }

    public function languages()
{
    return $this->belongsToMany(
        UserLanguage::class, 'user_language',   'user_id',  'language_id'
    );

}

}
