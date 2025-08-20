<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPackages extends Model
{
    protected $table = 'booking_packages';

        public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

      public function coachPackage()
    {
        return $this->belongsTo(UserServicePackage::class, 'package_id');
    }

}