<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_service'; // ✅ Update this if your table name is different

    protected $fillable = ['user_id', 'service_id'];
    public $timestamps = true;

        public function servicename()
{
    return $this->belongsTo(Service::class, 'service_id');
}
}
