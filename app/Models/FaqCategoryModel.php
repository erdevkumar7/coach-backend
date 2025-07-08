<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategoryModel extends Model
{
    protected $table = 'faq_category_model';

    public function faqs()
    {
        return $this->hasMany(FaqModel::class, 'category_id');
    }

}
