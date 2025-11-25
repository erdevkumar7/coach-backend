<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqCategoryModel;
class FaqController extends Controller
{
    // public function Getfaqs() {
    //     $categories = FaqCategoryModel::with('faqs')->get();
    //     return $categories;
    // }
    
        public function Getfaqs()
    {
        $categories = FaqCategoryModel::with(['faqs' => function ($query) {
            $query->where('is_active', 1)
                ->orderBy('position', 'ASC');
        }])
        ->orderBy('id', 'ASC')
        ->get();
       return $categories;
        // return response()->json([
        //     'success' => true,
        //     'message' => 'FAQs fetched successfully.',
        //     'data' => $categories
        // ], 200);
    }

}
