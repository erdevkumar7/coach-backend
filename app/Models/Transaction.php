<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

       protected $fillable = [
        'user_id',
        'txn_id',
        'coach_id',
        'package_id',
        'amount',
        'currency',
        'txn_date',
        'responce_text',
        'status',
        'payment_id',
        'payment_method_id',
    ];

       public function coachPackages()
    {
        return $this->belongsTo(\App\Models\UserServicePackage::class, 'package_id', 'id');
    }

        public function coach(): BelongsTo
    {
        return $this->belongsTo(\App\Models\UserServicePackage::class, 'coach_id', 'id');
    }

    // public function deliveryMode()
    // {
    //     return $this->belongsTo(DeliveryMode::class, 'delivery_mode');
    // }

    // public function priceModel()
    // {
    //     return $this->belongsTo(master_price_model::class, 'price_model');
    // }
}