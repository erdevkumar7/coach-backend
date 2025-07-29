<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserServicePackage extends Model
{
    protected $fillable = [
        'coach_id',
        'title',
        'package_status',
        'short_description',
        'coaching_category',
        'description',
        'focus',
        // 'coaching_type',
        'delivery_mode',
        'session_count',
        'session_duration',
        'session_format',
        'age_group',
        'price',
        'currency',
        'booking_slot',
        'booking_window',
        'cancellation_policy',
        'rescheduling_policy',
        'media_file',
        'status',
        'media_file',
        'media_original_name',
        'price_model',
        'booking_slots',
        'booking_availability',
        'booking_availability_start',
        'booking_availability_end'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }

    public function deliveryMode()
    {
        return $this->belongsTo(DeliveryMode::class, 'delivery_mode');
    }

    public function sessionFormat()
    {
        return $this->belongsTo(master_session_format::class, 'session_format');
    }

    public function priceModel()
    {
        return $this->belongsTo(master_price_model::class, 'price_model');
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class, 'age_group');
    }

     public function coachingCategory()
    {
        return $this->belongsTo(CoachingCat::class, 'coaching_category');
    }
}