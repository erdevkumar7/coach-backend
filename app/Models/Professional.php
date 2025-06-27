<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $table = 'user_professional';

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
}
