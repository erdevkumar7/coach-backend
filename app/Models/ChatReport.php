<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatReport extends Model
{
        use HasFactory;

    protected $table = 'chat_reports';

    protected $fillable = [
        'reported_by_id',
        'reported_against_id',
        'reported_by_type',
        'reported_against_type',
        'reason'
    ];
}
