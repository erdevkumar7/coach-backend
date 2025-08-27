<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

       protected $fillable = [
        'user_id',
        'txn_id',
        'package_id',
        'amount',
        'currency',
        'txn_date',
        'responce_text',
        'status',
    ];
}