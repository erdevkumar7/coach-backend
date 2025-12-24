<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CoachMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoachMatchingController extends Controller
{
    protected $matchingService;

    public function __construct(CoachMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Match coaches based on user preferences
     * POST /api/coaches/match
     */
    public function match(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'initial_goal' => 'required|string|max:500',
            'desired_outcome' => 'required|string|max:500',
            // 'coaching_style' => 'required|in:A,B,C',
            'industry_experience' => 'required|string',
            'language' => 'required|string',
            // 'budget' => 'required|string',
            'mode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $matches = $this->matchingService->matchCoaches($request->all());

            return response()->json([
                'success' => true,
                'matches' => $matches,
                'total_matches' => count($matches),
                'user_preferences' => $request->only([
                    'initial_goal', 'desired_outcome', 'coaching_style',
                    'language', 'budget', 'mode'
                ])
            ]);
        } catch (\Exception $e) {
            \Log::error('Coach matching failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to find coach matches',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all coaches (with optional filters)
     * GET /api/coaches
     */
    public function index(Request $request)
    {
        try {
            $query = \App\Models\User::where('user_type', 3)
                ->where('user_status', 1)
                ->where('is_published', 1)
                ->where('is_deleted', 0)
                ->with(['services', 'languages', 'reviews']);

            // Apply filters
            if ($request->has('language')) {
                $query->whereHas('languages', function($q) use ($request) {
                    $q->where('first_name', $request->language);
                });
            }

            if ($request->has('is_featured')) {
                $query->where('is_featured', 1);
            }

            $coaches = $query->paginate(12);

            return response()->json([
                'success' => true,
                'coaches' => $coaches
            ]);
        } catch (\Exception $e) {
            \Log::error('Fetching coaches failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch coaches',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}