<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscription';

        protected $fillable = ['user_id',
                           'plan_id', 
                           'amount', 
                           'start_date', 
                           'end_date', 
                           'payment_id', 
                           'txn_id', 
                           'status'];

    public function subscription_plan()
    {
        return $this->belongsTo(Subscription::class, 'plan_id', 'id');
    }
}
