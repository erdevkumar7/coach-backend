<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;


class SimilarCoachesController extends Controller
{
    public function SimilarCoaches(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'coach_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $coachId = $request->input('coach_id');

        $user_detail = Professional::where('user_id', $coachId)->first();


        if (!$user_detail) {
            return response()->json([
                'status' => false,
                'message' => 'User professional details not found',
            ], 404);
        }

        $coach_subtype = $user_detail->coach_subtype;

            $similarCoaches = Professional::with('user')
            ->where('coach_subtype', $coach_subtype)
            ->where('user_id', '!=', $coachId)
            ->limit(3)
            ->get();

        if ($similarCoaches->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No similar coaches found',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Similar coaches data list',
            'data' => $similarCoaches
        ]);
    }
}
