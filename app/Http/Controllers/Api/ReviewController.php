<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReviewController extends Controller
{
    public function Reviews()
    {

        try{
            $Reviews = Review::get();
            if ($Reviews->isEmpty()) {
                return response()->json(['message' => 'No review found'], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'All Reviews ',
                'data' => $Reviews
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function UserReviews(Request $request)
    {

        try {
            // Validate user_id only, since you're not using coach_id below
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user_id = $request->input('user_id');

            $reviews = Review::with(['coach:id,first_name,last_name,display_name,profile_image'])
            ->where('user_id', $user_id)
            ->get();


            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'All reviews fetched successfully.',
                'data' => $reviews
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function UserReviewView(Request $request)
    {

        try {
            // Validate user_id only, since you're not using coach_id below
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'coach_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user_id = $request->input('user_id');
            $coach_id = $request->input('coach_id');

            $review = Review::with(['coach:id,first_name,last_name,display_name,profile_image'])
            ->where('user_id', $user_id)
            ->where('coach_id', $coach_id)
            ->first();

            if (!$review) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'review fetched successfully.',
                'data' => $review
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // User review update
    public function UserReviewUpdate(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id'                    => 'required|integer',
                'user_id'               => 'required|integer',
                'review_text'           => 'nullable|string',
                'rating'                => 'nullable|numeric|between:1,5',
                'status'                => 'nullable|in:0,1',
                'user_status'           => 'nullable|in:0,1,2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            $id = $request->input('id');
            $review = Review::find($id);
            if (!$review) {
                return response()->json(['status' => false, 'message' => 'review not found.'], 404);
            }

            // Update other fields if present
            $fields = [
                'review_text', 'rating', 'status', 'user_status'
            ];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $review->$field = $request->$field;
                }
            }

            $review->save();

            return response()->json([
                'status' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // User Reply review
    public function UserReviewReply(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id'                    => 'required|integer',
                'user_id'               => 'required|integer',
                'coach_id'              => 'required|integer',
                'review_text'           => 'nullable|string',
                'rating'                => 'nullable|numeric|between:1,5',
                'status'                => 'nullable|in:0,1',
                'user_status'           => 'nullable|in:0,1,2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            $review = Review::where('id', $request->id)
                        ->where('user_id', $request->user_id)
                        ->where('coach_id', $request->coach_id)
                        ->whereNull('reply_id') // ensure it's a parent review
                        ->first();

            if (!$review) {
                return response()->json(['status' => false, 'message' => 'reply id wrong'], 404);
            }


            $review = Review::create([
                'user_id'               => $request->user_id,
                'coach_id'              => $request->coach_id,
                'review_text'           => $request->review_text,
                'rating'                => $request->rating,
                'status'                => $request->status,
                'user_status'           => $request->user_status,
                'reply_id'              => $request->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Review reply successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }







    // Coach review


    public function CoachReviews(Request $request)
    {

        try {
            // Validate user_id only, since you're not using coach_id below
            $validator = Validator::make($request->all(), [
                'coach_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $coach_id = $request->input('coach_id');

            $reviews = Review::with('user')
            ->where('coach_id', $coach_id)
            ->where('user_status', 1)
            ->get();


            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'All reviews fetched successfully.',
                'data' => $reviews
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
