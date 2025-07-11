<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $table = 'user_document';

    protected $fillable = [
        'user_id',
        'document_file',
        'original_name',
        'document_type',
    ];

}
