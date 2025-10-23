<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachHistory extends Model
{
    protected $table = "coach_history";
    protected $fillable = [
        'coach_id',
        'package_id',
        'viewer_id',
        'viewer_type',
        'view_count'
    ];

    public function package()
    {
        return $this->belongsTo(UserServicePackage::class, 'package_id');
    }
}
