<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqCategoryModel;
class FaqController extends Controller
{
    public function Getfaqs() {
        $categories = FaqCategoryModel::with('faqs')->get();
        return $categories;
    }
}
