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
        'coaching_type',
        'delivery_mode',
        'session_count',
        'session_duration',
        'target_audience',
        'price',
        'currency',
        'booking_slot',
        'booking_window',
        'cancellation_policy',
        'rescheduling_policy',
        'media_file',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }
}
