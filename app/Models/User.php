<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *

     * @var list<string>
     */

    protected $fillable = [
        'first_name',
        'email',
        'password',
        'user_type',
        'country_id',
        'user_timezone',
        'last_name'
    ];

    //   protected $fillable = ['name', 'email', 'password'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    // public function services()
    // {
    //     return $this->hasMany(UserService::class, 'user_id', 'id');
    // }

    // public function languages()
    // {
    //     return $this->hasMany(UserLanguage::class, 'user_id', 'id');
    // }

    // public function userProfessional()
    // {
    //     return $this->hasOne(Professional::class, 'user_id');
    // }

    public function services()
    {
        return $this->hasMany(UserService::class, 'user_id');
    }

    public function languages()
    {
        return $this->hasMany(UserLanguage::class, 'user_id');
    }


    public function userProfessional()
    {
        return $this->hasOne(Professional::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\MasterCountry::class, 'country_id', 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(\App\Models\MasterState::class, 'state_id', 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\MasterCity::class, 'city_id', 'city_id');
    }

    public function enquiries()
    {
        return $this->hasMany(MasterEnquiry::class, 'user_id');
    }
}
