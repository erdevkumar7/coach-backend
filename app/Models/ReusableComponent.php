<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReusableComponent extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'type',
        'html',
        'css',
        'components',
    ];

    // Automatically cast 'components' field as an array
    protected $casts = [
        'components' => 'array',
    ];
}
