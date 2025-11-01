<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscription';

        protected $fillable = ['user_id',
                            'coach_name',
                           'plan_id', 
                            'plan_name',
                            'plan_content',
                           'amount', 
                           'start_date', 
                           'end_date', 
                           'payment_id', 
                           'txn_id',
                            'payment_method', 
                            'payment_type',
                            'payment_last4',
                           'status'];

    public function subscription_plan()
    {
        return $this->belongsTo(Subscription::class, 'plan_id', 'id');
    }
}
