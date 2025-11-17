<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
   use HasFactory;

    protected $table = 'home_settings';

    protected $fillable = [
        'section_name',
        'title',
        'subtitle',
        'description',
        'image',
        'is_active',
    ];
}
