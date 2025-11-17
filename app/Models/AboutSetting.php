<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
      use HasFactory;

    protected $table = 'about_settings';

    protected $fillable = [
        'section_name',
        'title',
        'subtitle',
        'description',
        'image',
        'video',
        'is_active',
    ];
}
