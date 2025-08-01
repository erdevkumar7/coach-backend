<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteCoach;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class FavoriteCoachController extends Controller
{
    public function addRemoveCoachFavorite(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'coach_id' => 'required|integer',
            ]);

            $user_valid = User::where('id', $user->id)
                    ->where('user_type', '3')
                    ->first();

            if (!empty($user_valid)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not a valid user',
                ], 422);
            }


            // $valid_user = User::where('id')
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $existingFavorite = FavoriteCoach::where('coach_id', $request->coach_id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingFavorite) {
                $existingFavorite->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Coach removed from favorites.',
                ]);
            } else {

                //return $request->coach_id;
                $newFavorite = FavoriteCoach::create([
                    'coach_id' => $request->coach_id,
                    'user_id'  => $user->id, // ✅ REQUIRED
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Coach added to favorites.',
                    //'data' => $newFavorite,
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

public function coachFavoriteList(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $perPage = $request->input('per_page', 10); // default 10 per page
        $page = $request->input('page', 1);

        $favorites = FavoriteCoach::with([
                'coach:id,first_name,last_name,professional_title,company_name,profile_image',
                'coach.reviews',
                'coach.coachSubtypes'
            ])
            ->where('user_id', $user->id)
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform each item
        $favorites->getCollection()->transform(function ($item) {
            $coach = $item->coach;

            if ($coach && $coach->profile_image) {
                $coach->profile_image = asset('public/uploads/profile_image/' . $coach->profile_image);
            }

            $item->coach_subtypes = $coach->coachSubtypes->pluck('subtype_name');
            unset($coach->coachSubtypes); // optional: to avoid redundancy

            return $item;
        });

        return response()->json([
            'status' => true,
            'message' => 'Favorites Coach list.',
            'data' => $favorites->items(),
            'pagination' => [
                'total' => $favorites->total(),
                'per_page' => $favorites->perPage(),
                'current_page' => $favorites->currentPage(),
                'last_page' => $favorites->lastPage(),
                'from' => $favorites->firstItem(),
                'to' => $favorites->lastItem(),
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}