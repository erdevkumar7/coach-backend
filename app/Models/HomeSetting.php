<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
   use HasFactory;

    protected $table = 'home_settings';

    protected $fillable = [
        'section_key',
        'plan_title',
        'plan_subtitle',
        'plan_description',
        'image',
        'is_active',
    ];
}
