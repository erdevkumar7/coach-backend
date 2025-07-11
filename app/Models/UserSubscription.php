<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscription';

    public function subscription_plan()
    {
        return $this->belongsTo(Subscription::class, 'plan_id', 'id');
    }
}
