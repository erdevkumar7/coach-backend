<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;


class SimilarCoachesController extends Controller
{
    public function SimilarCoaches(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'coach_id'  => 'required',
        ]);


        $coachId = $request->input('coach_id');

        // $user_detail = Professional::where('user_id', $coachId)->first();
        // if (!$user_detail) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'User professional details not found',
        //     ], 404);
        // }

        // $coach_type = $user_detail->coach_type;

        // $similarCoaches = Professional::with('user')
        //     ->where('coach_type', $coach_type)
        //     ->where('user_id', '!=', $coachId)
        //     ->limit(5)
        //     ->get();

        $currentCoach = User::with('userProfessional')->find($coachId);
        $coachTypeId = $currentCoach->userProfessional->coach_type ?? null;

        $similarCoaches = User::with(['services.servicename'])
            ->where('id', '!=', $currentCoach->id)
            ->where('user_status', 1)
            ->where('user_type', 3)
            ->whereHas('userProfessional', function ($q) use ($coachTypeId) {
                $q->where('coach_type', $coachTypeId);
            })
            ->limit(5)
            ->get();


        $similarCoaches = $similarCoaches->map(function ($coach) {
            return [
                'id' => $coach->id,
                'first_name' => $coach->first_name,
                'last_name' => $coach->last_name,
                'professional_title' => $coach->professional_title,
                'company_name' => $coach->company_name,
                'profile_image' => $coach->profile_image
                    ? url('public/uploads/profile_image/' . $coach->profile_image)
                    : '',
                'service_names' => $coach->services->pluck('servicename.service')->filter()->values(),
            ];
        });


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
