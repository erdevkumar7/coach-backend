<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{


    public function userReviewSubmit(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $user_id = $user->id;

            // Validation
            $validator = Validator::make($request->all(), [
                'coach_id'    => 'required|integer',
                'review_text' => 'nullable|string',
                'rating'      => 'required|numeric|between:1,5',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // ✅ Check if review already exists for same user, coach & booking
            $existingReview = Review::where('user_id', $user_id)
                ->where('coach_id', $request->coach_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already submitted a review for this coach.',
                ], 409); // Conflict
            }

            // Create new review
            $review = Review::create([
                'user_id'     => $user_id,
                'coach_id'    => $request->coach_id,
                'review_text' => $request->review_text,
                'rating'      => $request->rating,
                'user_status' => 1, // draft/pending
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Review submitted successfully.',
                'data'    => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while submitting review.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function userReviews(Request $request)
    {

        try {

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }
            $user_id = $user->id;


            $reviews = Review::with(['coach:id,first_name,last_name,display_name,profile_image'])
                ->where('user_id', $user_id)
                ->where('is_deleted', 0)
                ->whereNull('reply_id')
                ->orderBy('created_at', 'desc')
                ->get();

            // Append full path to profile_image
            // $reviews->map(function ($review) {
            //     if ($review->coach && $review->coach->profile_image) {
            //         $review->coach->profile_image = url('public/uploads/profile_image/' . $review->coach->profile_image);
            //     } else {
            //         $review->coach->profile_image = null; // default if no image
            //     }
            //     return $review;
            // });

            $reviews->map(function ($review) {
                if ($review->user && $review->user->profile_image) {
                    // Check if already a full URL
                    if (!filter_var($review->user->profile_image, FILTER_VALIDATE_URL)) {
                        $review->user->profile_image = url('public/uploads/profile_image/' . $review->user->profile_image);
                    }
                } else {
                    $review->user->profile_image = null; // default if no image
                }
                return $review;
            });


            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'All reviews retrieved successfully.',
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

    public function userReviewView(Request $request)
    {

        try {

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }
            $user_id = $user->id;

            // Validate user_id only, since you're not using coach_id below
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            $id = $request->input('id');

            $review = Review::with(['coach:id,first_name,last_name,display_name,profile_image'])
                ->where('user_id', $user_id)
                ->where('id', $id)
                ->where('is_deleted', 0)
                ->first();

            // Append full path to profile_image
            // Fix image path for single record
            if ($review->coach && $review->coach->profile_image) {
                $review->coach->profile_image = url('public/uploads/profile_image/' . $review->coach->profile_image);
            } else {
                $review->coach->profile_image = null;
            }

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
    public function userReviewUpdate(Request $request)
    {
        try {

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }
            //$coach_id = $user->id;

            $validator = Validator::make($request->all(), [
                'id'                    => 'required|integer',
                'review_text'           => 'nullable|string',
                'rating'                => 'nullable|numeric|between:1,5',
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
                'review_text',
                'rating',
                'user_status'
            ];

            $updatedData = [];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $review->$field = $request->$field;
                    $updatedData[$field] = $request->$field;
                }
            }

            $review->save();

            return response()->json([
                'status' => true,
                'message' => 'Review updated successfully',
                'data' => $updatedData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }





    public function userReviewDelete($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'status' => false,
                    'message' => 'Review not found.',
                ], 404);
            }

            if ($review->user_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to delete this review.',
                ], 403);
            }

            $review->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Review deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while deleting review.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }







    // Coach review


    public function coachReviewsBackend(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $coach_id = $user->id;
            // Fetch reviews
            $reviews = Review::with([
                'user:id,first_name,last_name,display_name,profile_image',
                'reply:id,reply_id,review_text' // only id + text will come now
            ])
                ->where('coach_id', $coach_id)
                ->where('user_status', 1)
                ->where('is_deleted', 0)
                ->whereNull('reply_id') // parent reviews only
                ->orderBy('created_at', 'desc')
                ->get();


            $replyedReview = [];
            $replyedReview = Review::where('reply_id', $coach_id)
                ->where('user_status', 1)
                ->where('is_deleted', 0)
                ->first();


            // Total count & average rating
            $totalReviews = $reviews->count();
            $averageRating = $reviews->avg('rating'); // auto-null if no reviews

            // Append full path to profile_image
            $reviews->map(function ($review) {
                if ($review->user && $review->user->profile_image) {
                    if (!filter_var($review->user->profile_image, FILTER_VALIDATE_URL)) {
                        $review->user->profile_image = url('public/uploads/profile_image/' . $review->user->profile_image);
                    }
                } else {
                    $review->user->profile_image = null;
                }
                return $review;
            });

            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found',
                    'total_reviews' => 0,
                    'average_rating' => 0
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'All reviews fetched successfully.',
                'total_reviews' => $totalReviews,
                'average_rating' => round($averageRating, 2), // keep 2 decimals
                'data' => $reviews,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function coachReviewView(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $coach_id = $user->id;

            // Validate
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $id = $request->input('id');

            $review = Review::with(['user:id,first_name,last_name,display_name,profile_image'])
                ->where('coach_id', $coach_id)
                ->where('id', $id)
                // ->where('user_status', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$review) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found'
                ], 404);
            }

            // Append full path to profile_image
            if ($review->user && $review->user->profile_image) {
                if (!filter_var($review->user->profile_image, FILTER_VALIDATE_URL)) {
                    $review->user->profile_image = url('public/uploads/profile_image/' . $review->user->profile_image);
                }
            } else {
                $review->user->profile_image = null;
            }

            // Return only selected fields
            $responseData = [
                'id'          => $review->id,
                'review_text' => $review->review_text,
                'rating'      => $review->rating,
                'created_at'  => $review->created_at,
                'updated_at'  => $review->updated_at,
                'user'        => [
                    'id'            => $review->user->id,
                    'first_name'    => $review->user->first_name,
                    'last_name'     => $review->user->last_name,
                    'display_name'  => $review->user->display_name,
                    'profile_image' => $review->user->profile_image,
                ]
            ];

            return response()->json([
                'status' => true,
                'message' => 'Review fetched successfully.',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // User Reply review
    public function coachReplyToUserReview(Request $request)
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
                'review_id'   => 'required|integer', // parent review id
                'review_text' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // Check parent review exists
            $parentReview = Review::where('id', $request->review_id)
                ->where('is_deleted', 0)
                ->where('user_status', 1)
                ->first();

            if (!$parentReview) {
                return response()->json([
                    'status' => false,
                    'message' => 'Parent review not found',
                ], 404);
            }

            // Check if reply already exists
            $existingReply = Review::where('reply_id', $request->review_id)->first();
            if ($existingReply) {

                // If a reply already exists, update its text instead of creating a new one
                $existingReply->update([
                    'review_text' => $request->review_text,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Your reply has been updated successfully.',
                    'data'    => $existingReply,
                ], 200);
            }

            // Create reply
            $reply = Review::create([
                'coach_id'    => $user->id, // logged-in coach replying
                'user_id'     => $parentReview->user_id, // reply to same user
                'review_text' => $request->review_text,
                'reply_id'    => $request->review_id, // link to parent
                'coach_status' => 1,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Reply submitted successfully',
                'data'    => $reply
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while replying.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    // Coach review update
    public function coachReviewStatusUpdate(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }
            $coach_id = $user->id;

            $validator = Validator::make($request->all(), [
                'id'                    => 'required|integer',
                'coach_status'          => 'nullable|in:0,1,2',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            $id = $request->input('id');
            $review = Review::where('coach_id', $coach_id)->find($id);
            if (!$review) {
                return response()->json(['status' => false, 'message' => 'review not found.'], 404);
            }

            // Update other fields if present
            $fields = [
                'coach_status'
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



    // Coach reviews frontend website

    public function coachReviewsFrontend(Request $request)
    {
        try {
            $coach_id = $request->coach_id;
            $page     = $request->input('page', 1); // default 1st page

            // First page = 3 reviews, later pages = 5 reviews
            $perPage = $page == 1 ? 3 : 5;

            $reviews = Review::with('user:id,first_name,last_name,display_name,profile_image')
                ->where('coach_id', $coach_id)
                ->where('user_status', 1)
                ->where('coach_status', 1)
                ->where('is_deleted', 0)
                ->whereNull('reply_id')
                ->latest()
                ->paginate($perPage, ['*'], 'page', $page);

            if ($reviews->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No review found'
                ], 404);
            }

            // Transform response
            $reviewsData = $reviews->getCollection()->map(function ($review) {
                $profileImage = null;
                if ($review->user && $review->user->profile_image) {
                    $profileImage = filter_var($review->user->profile_image, FILTER_VALIDATE_URL)
                        ? $review->user->profile_image
                        : url('public/uploads/profile_image/' . $review->user->profile_image);
                }

                return [
                    'id'          => $review->id,
                    'coach_id'    => $review->coach_id,
                    'review_text' => $review->review_text,
                    'rating'      => $review->rating,
                    'created_at'  => $review->created_at->format('M d, Y H:i'),
                    'user'        => [
                        'id'            => $review->user->id,
                        'first_name'    => $review->user->first_name,
                        'last_name'     => $review->user->last_name,
                        'display_name'  => $review->user->display_name,
                        'profile_image' => $profileImage,
                    ]
                ];
            });

            return response()->json([
                'status'  => true,
                'message' => 'Reviews fetched successfully.',
                'data'    => $reviewsData,
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page'    => $reviews->lastPage(),
                    'per_page'     => $reviews->perPage(),
                    'total'        => $reviews->total(),
                ]
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
