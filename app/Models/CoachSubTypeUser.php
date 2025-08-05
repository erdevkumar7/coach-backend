<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachSubTypeUser extends Model
{
        use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coach_subtype_user'; // ✅ Update this if your table name is different

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coach_subtype_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    // public function coachUsers()
    // {
    //     return $this->belongsToMany(User::class, 'coach_subtype_user');
    // }
        public function coachsubtype()
    {
        return $this->belongsTo(CoachSubType::class, 'id');
    }


        public function coachSubtypeid()
    {
        // return $this->belongsTo(CoachSubType::class, 'coach_subtype_id');
        return $this->belongsTo(CoachSubType::class, 'coach_subtype_id');
    }
}