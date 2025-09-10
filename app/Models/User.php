<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\UserNotificationSetting;
use App\Models\UserPrivacySetting;
use  App\Models\CoachSubType;
use Illuminate\Database\Eloquent\Relations\HasMany;


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
        'last_name',
        'email',
        'password',
        'user_type',
        'country_id',
        'user_timezone',
        'google_id',
        'avatar',
        'user_status',
        'email_verified',
        'is_social',
        'is_deleted',
        'is_verified',
        'is_corporate',
        'is_active',
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

    public function coachsubtypeuser()
    {
        return $this->hasMany(CoachSubTypeUser::class, 'user_id');
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

    public function notificationSettings()
    {
        return $this->hasOne(UserNotificationSetting::class, 'user_id');
    }

    public function privacySettings()
    {
        return $this->hasOne(UserPrivacySetting::class,'user_id');
    }


	public function userServicePackages(): HasMany
    {
        return $this->hasMany(UserServicePackage::class, 'coach_id', 'id');
    }

    public function coachSubtypes()
    {
        return $this->belongsToMany(CoachSubType::class, 'coach_subtype_user','user_id', 'coach_subtype_id');
    }

    public function UserDocument()
    {
        return $this->hasMany(UserDocument::class, 'user_id');
    }


    public function CoachRequest(): HasMany
    {
         return $this->hasMany(CoachingRequest::class, 'coach_id','id');
    }
 
    public function reviews()
    {
        return $this->hasMany(Review::class, 'coach_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class, 'receiver_id', 'id')
            ->latestOfMany(); // Laravel shortcut for "orderBy created_at desc limit 1"
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id', 'id')
            ->where('is_read', 0);
    }

       public function UserRequest(): HasMany
    {
         return $this->hasMany(CoachingRequest::class, 'user_id','id');
    }

    	public function CoachBookingPackages(): HasMany
    {
        return $this->hasMany(BookingPackages::class, 'coach_id', 'id');
    }

       	public function UserBookingPackages(): HasMany
    {
        return $this->hasMany(BookingPackages::class, 'user_id', 'id');
    }



}