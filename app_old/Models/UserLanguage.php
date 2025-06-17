<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_language'; // ✅ Update this if your table name is different

    protected $fillable = ['user_id', 'language_id'];
    public $timestamps = true;


        public function languagename()
{
    return $this->belongsTo(Language::class, 'language_id');
}
}
