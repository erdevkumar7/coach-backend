<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageHistory extends Model
{
    protected $table = "package_history";
    protected $fillable = [
        'coach_id',
        'package_id',
        'viewer_id',
        'viewer_type',
        'view_count'
    ];
}
