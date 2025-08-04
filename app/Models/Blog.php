<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'master_blogs';

public function getBlogImageAttribute($value)
{
    return $value ? asset('public/uploads/blog_files/' . $value) : null;
}
public function getBlogVideoAttribute($value)
{
    return $value ? asset('public/uploads/blog_files/' . $value) : null;
}
}