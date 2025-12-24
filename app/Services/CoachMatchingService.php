<?php

// ==========================================
// SIMPLIFIED AI MATCHING SERVICE
// Uses ONLY existing fields from your database
// NO MIGRATION NEEDED!
// ==========================================

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CoachMatchingService
{
    /**
     * Match coaches using ONLY existing database fields
     */
    public function matchCoaches(array $preferences)
    {
        // 1. Generate embedding for user's goals
        $userProfileText = $this->buildUserProfileText($preferences);
        $userEmbedding = $this->generateEmbedding($userProfileText);
        // dd($userEmbedding, $userProfileText);
        // \Log::info('User Embedding Generated', [
        //     'text' => $userProfileText,
        //     'embedding_success' => is_array($userEmbedding),
        //     'embedding_length' => is_array($userEmbedding) ? count($userEmbedding) : null,
        //     'sample' => is_array($userEmbedding) ? array_slice($userEmbedding, 0, 5) : null, // first 5 values
        // ]);

        // 2. Get active coaches
        $coaches = User::where('user_type', 3)
            ->where('user_status', 1)
            ->where('is_published', 1)
            ->where('is_deleted', 0)
            // ->where('profile_status', 'complete')
            ->whereNotNull('embedding')
            ->with(['services', 'languages', 'coachSubtypes', 'reviews'])
            ->get();

        // echo $coaches;
        // echo "Total Coaches Retrieved: " . $coaches->count() . "\n";

        // 3. Score coaches
        $scoredCoaches = $coaches->map(function ($coach) use ($preferences, $userEmbedding) {
            // return $this->scoreCoach($coach, $preferences, $userEmbedding);
            $scoredCoach = $this->scoreCoach($coach, $preferences, $userEmbedding);

            // \Log::info('Coach Scored', [
            //     'coach_id' => $coach->id,
            //     'name' => $coach->display_name ?? $coach->first_name . ' ' . $coach->last_name,
            //     'match_score' => $scoredCoach->match_score,
            //     'semantic' => $scoredCoach->score_breakdown['semantic'] ?? 0,
            //     'language_match' => $scoredCoach->score_breakdown['language'] ?? 0,
            //     'topics' => $scoredCoach->score_breakdown['topics'] ?? 0,
            // ]);

            return $scoredCoach;
        });

        // 4. Return top matches
        return $scoredCoaches
            ->filter(fn($coach) => $coach->match_score > 40)
            ->sortByDesc('match_score')
            ->take(5)
            ->values()
            ->map(fn($coach) => $this->formatCoachResponse($coach));
    }

    /**
     * Score coach using ONLY existing fields
     */
    private function scoreCoach($coach, $preferences, $userEmbedding)
    {
        $scores = [
            'semantic' => 0,      // From embedding (EXISTING)
            'language' => 0,      // From languages table (EXISTING)
            'mode' => 0,          // From delivery_mode (EXISTING)
            'topics' => 0,        // From coaching_topics (EXISTING)
            'reviews' => 0,       // From reviews table (EXISTING)
            'experience' => 0,    // From exp_and_achievement (EXISTING)
            'availability' => 0,  // From is_online, is_avail_for_relavant (EXISTING)
        ];

        // dd($coach);

        // 1. SEMANTIC SIMILARITY - 50 points (Most Important!)
        // if ($userEmbedding && $coach->embedding) {
        //     $coachEmbedding = is_string($coach->embedding)
        //         ? json_decode($coach->embedding, true)
        //         : $coach->embedding;

        //     if ($coachEmbedding) {
        //         $similarity = $this->cosineSimilarity($userEmbedding, $coachEmbedding);
        //         $scores['semantic'] = $similarity * 50;
        //     }
        // }
        // 1. SEMANTIC SIMILARITY - 50 points (Most Important!)
        if ($userEmbedding && $coach->embedding) {
            $coachEmbedding = is_string($coach->embedding)
                ? json_decode($coach->embedding, true)
                : $coach->embedding;

            // \Log::info('Semantic Debug Details', [
            //     'coach_id' => $coach->id,
            //     'user_embedding_type' => gettype($userEmbedding),
            //     'user_embedding_count' => is_array($userEmbedding) ? count($userEmbedding) : 'N/A',
            //     'coach_embedding_raw_type' => gettype($coach->embedding),
            //     'coach_embedding_decoded_type' => gettype($coachEmbedding),
            //     'coach_embedding_count' => is_array($coachEmbedding) ? count($coachEmbedding) : 'N/A',
            //     'counts_match' => (is_array($userEmbedding) && is_array($coachEmbedding)) ? (count($userEmbedding) === count($coachEmbedding)) : false,
            // ]);

            if (is_array($coachEmbedding) && is_array($userEmbedding) && count($userEmbedding) === count($coachEmbedding)) {
                $similarity = $this->cosineSimilarity($userEmbedding, $coachEmbedding);
                $scores['semantic'] = round($similarity * 50, 2);
                // \Log::info('Semantic Score Calculated', [
                //     'coach_id' => $coach->id,
                //     'similarity' => $similarity,
                //     'semantic_points' => $scores['semantic']
                // ]);
            } else {
                $scores['semantic'] = 0;
            }
        } //else {
        //     \Log::warning('Semantic skipped - missing embedding', [
        //         'coach_id' => $coach->id,
        //         'has_user_embedding' => !empty($userEmbedding),
        //         'has_coach_embedding' => !empty($coach->embedding),
        //     ]);
        // }

        // 2. LANGUAGE MATCH - 20 points
        // $coachLanguages = $coach->languages->pluck('name')->toArray();
        // if (in_array($preferences['language'], $coachLanguages)) {
        //     $scores['language'] = 20;
        // } elseif (!empty($coachLanguages)) {
        //     $scores['language'] = 10; // Partial score
        // }

        // LANGUAGE MATCH - 20 points
        $coachLanguages = $coach->languages
            ->pluck('languagename.language')
            ->filter()
            ->map(fn ($l) => strtolower(trim($l)))
            ->toArray();

        $preferredLanguage = strtolower(trim($preferences['language']));

        if (in_array($preferredLanguage, $coachLanguages)) {
            $scores['language'] = 20;
        } elseif (!empty($coachLanguages)) {
            $scores['language'] = 10; // Partial score
        }


        // 3. DELIVERY MODE - 10 points
        $modeScore = $this->calculateModeScore($preferences['mode'], $coach->delivery_mode);
        $scores['mode'] = $modeScore;

        // 4. TOPICS RELEVANCE - 10 points
        if ($coach->coaching_topics) {
            $topics = is_array($coach->coaching_topics)
                ? $coach->coaching_topics
                : explode(',', $coach->coaching_topics);

            $scores['topics'] = $this->calculateTopicsScore(
                $preferences['initial_goal'] . ' ' . $preferences['desired_outcome'],
                $topics
            );
        }

        // 5. REVIEWS & RATING - 10 points
        $reviewCount = $coach->reviews->count();
        // print_r('Review Count: ' . $reviewCount . "\n");die;
        if ($reviewCount > 0) {
            $avgRating = $coach->reviews->avg('rating') ?? 0;
            $scores['reviews'] = min(10, ($avgRating / 5) * 10);
        }

        // 6. EXPERIENCE - 5 points
        if ($coach->exp_and_achievement) {
            $expLength = strlen($coach->exp_and_achievement);
            $scores['experience'] = min(5, $expLength / 200);
        }

        // 7. AVAILABILITY - 5 points
        if ($coach->is_avail_for_relavant == 1 && $coach->is_online == 1) {
            $scores['availability'] = 5;
        } elseif ($coach->is_avail_for_relavant == 1) {
            $scores['availability'] = 3;
        }

        // Calculate total
        $totalScore = array_sum($scores);
        $matchReason = $this->generateMatchExplanation($coach, $preferences, $scores);

        $coach->score_breakdown = $scores;
        $coach->match_score = round($totalScore, 2);
        $coach->match_percentage = min(100, round(($totalScore / 100) * 100));
        $coach->match_reason = $matchReason;

        return $coach;
    }

    /**
     * Calculate delivery mode score
     */
    private function calculateModeScore($userMode, $coachDeliveryMode)
    {
        if ($userMode === 'No preference') {
            return 5;
        }

        // Map according to DB values
        $modeMap = [
            1 => 'online',
            2 => 'In-person',
            3 => 'hybrid'
        ];

        $coachMode = $modeMap[$coachDeliveryMode] ?? null; // 'online' or null

        if (!$coachMode) {
            return 0;
        }

        $userMode = strtolower(trim($userMode));

        if ($coachMode === $userMode) {
            return 10;
        }

        // Hybrid matches partially
        if ($coachMode === 'hybrid') {
            return 8;
        }

        return 0;
    }


    /**
     * Calculate topics relevance
     */
    private function calculateTopicsScore($userGoals, $coachTopics)
    {
        if (empty($coachTopics)) {
            return 0;
        }

        $userGoalsLower = strtolower($userGoals);
        $score = 0;

        foreach ($coachTopics as $topic) {
            $topicWords = explode(' ', strtolower(trim($topic)));
            foreach ($topicWords as $word) {
                if (strlen($word) > 3 && strpos($userGoalsLower, $word) !== false) {
                    $score += 1;
                }
            }
        }

        return min(10, $score);
    }

    /**
     * Generate match explanation
     */
    private function generateMatchExplanation($coach, $preferences, $scores)
    {
        $coachName = $coach->display_name ?? ($coach->first_name . ' ' . $coach->last_name);
        $reasons = [];

        if ($scores['semantic'] > 30) {
            $reasons[] = "strong alignment with your goals";
        }

        if ($scores['topics'] > 5) {
            $reasons[] = "relevant expertise in your areas of interest";
        }

        if ($scores['reviews'] > 7) {
            $reviewCount = $coach->reviews->count();
            $reasons[] = "excellent track record ({$reviewCount} positive reviews)";
        }

        if ($scores['experience'] > 3) {
            $reasons[] = "extensive professional experience";
        }

        if (empty($reasons)) {
            $reasons[] = "personalized coaching approach";
        }

        // Get first topic/specialty
        $specialty = 'coaching';
        if ($coach->coaching_topics) {
            $topics = is_array($coach->coaching_topics)
                ? $coach->coaching_topics
                : explode(',', $coach->coaching_topics);
            $specialty = trim($topics[0]) ?? 'coaching';
        } elseif ($coach->professional_title) {
            $specialty = $coach->professional_title;
        }

        return sprintf(
            "%s specializes in %s with %s.",
            $coachName,
            $specialty,
            implode(', ', array_slice($reasons, 0, 2))
        );
    }

    /**
     * Format coach response
     */
    private function formatCoachResponse($coach)
    {
        // Calculate average rating from reviews
        $avgRating = $coach->reviews->count() > 0
            ? $coach->reviews->avg('rating')
            : 0;

        // Get coaching topics as array
        $specialties = [];
        if ($coach->coaching_topics) {
            $specialties = is_array($coach->coaching_topics)
                ? $coach->coaching_topics
                : array_map('trim', explode(',', $coach->coaching_topics));
        }

        return [
            'id' => $coach->id,
            'name' => $coach->display_name ?? ($coach->first_name . ' ' . $coach->last_name),
            'first_name' => $coach->first_name,
            'last_name' => $coach->last_name,
            'title' => $coach->professional_title ?? 'Professional Coach',
            'bio' => $coach->short_bio ?? $coach->detailed_bio,
            'photo' => $coach->profile_image
                ? url('uploads/profiles/' . $coach->profile_image)
                : null,
            'avatar' => $coach->avatar,
            'specialties' => $specialties,
            'languages' => $coach->languages->pluck('languagename.language')->toArray(),
            'delivery_mode' => $this->formatDeliveryMode($coach->delivery_mode),
            'rating' => round($avgRating, 1),
            'total_sessions' => $coach->CoachBookingPackages->count(),
            'total_reviews' => $coach->reviews->count(),
            'match_score' => $coach->match_score,
            'match_percentage' => $coach->match_percentage,
            'match_reason' => $coach->match_reason,
            'score_breakdown' => $coach->score_breakdown,
            'is_verified' => $coach->is_verified == 1,
            'is_featured' => $coach->is_featured == 1,
            'is_online' => $coach->is_online == 1,
            'location' => $this->formatLocation($coach),
            'profile_url' => url('/coach/' . $coach->id),
            'price_display' => 'Contact for pricing', // Since no price fields
        ];
    }

    /**
     * Format delivery mode
     */
    private function formatDeliveryMode($mode)
    {
        $modeMap = [
            1 => 'Online',
            2 => 'In-person',
            3 => 'Hybrid (Online & In-person)'
        ];
        return $modeMap[$mode] ?? 'Online';
    }

    /**
     * Format location
     */
    private function formatLocation($coach)
    {
        $parts = array_filter([
            $coach->city?->name,
            $coach->state?->name,
            $coach->country?->name
        ]);
        return implode(', ', $parts);
    }

    /**
     * Build user profile text
     */
    private function buildUserProfileText(array $prefs)
    {
        return sprintf(
            "Goal: %s. Desired outcome: %s. Language: %s.",
            $prefs['initial_goal'] ?? '',
            $prefs['desired_outcome'] ?? '',
            $prefs['language'] ?? 'English'
        );
    }

    public function generateEmbedding($text)
    {
        $cacheKey = 'embedding_' . md5($text);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withToken(config('services.openai.key'))
            ->timeout(15)->post('https://api.openai.com/v1/embeddings', [
                'model' => 'text-embedding-3-small',
                'input' => $text,
            ]);

            if ($response->successful()) {
                $embedding = $response->json()['data'][0]['embedding'];
                Cache::put($cacheKey, $embedding, now()->addDays(30));
                return $embedding;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Embedding generation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cosine similarity
     */
    private function cosineSimilarity($vec1, $vec2)
    {
        if (!is_array($vec1) || !is_array($vec2) || count($vec1) !== count($vec2)) {
            return 0;
        }

        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dotProduct += ($vec1[$i] ?? 0) * ($vec2[$i] ?? 0);
            $magnitude1 += ($vec1[$i] ?? 0) * ($vec1[$i] ?? 0);
            $magnitude2 += ($vec2[$i] ?? 0) * ($vec2[$i] ?? 0);
        }

        $magnitude = sqrt($magnitude1) * sqrt($magnitude2);

        return $magnitude > 0 ? $dotProduct / $magnitude : 0;
    }

    /**
     * Generate embeddings for all coaches
     */
    public function generateAllCoachEmbeddings($force = false)
    {
        // $coaches = User::where('user_type', 3)
        //     ->where('user_status', 1)
        //     ->where('is_deleted', 0)
        //     ->whereNull('embedding')
        //     ->get();
        $query = User::where('user_type', 3)
        ->where('user_status', 1)
        ->where('is_deleted', 0);

        // ✅ Only skip coaches with embeddings if NOT forcing
        if (!$force) {
            $query->whereNull('embedding');
        }

        $coaches = $query->get();

        $processed = 0;
        $failed = 0;

        foreach ($coaches as $coach) {
            try {
                $profileText = $this->buildCoachProfileText($coach);
                $embedding = $this->generateEmbedding($profileText);

                if ($embedding) {
                    // $coach->update(['embedding' => json_encode($embedding)]);
                    // $coach->embedding = json_encode($embedding);
                    // $coach->save();
                    // User::where('id', $coach->id)->update(['embedding' => json_encode($embedding)]);
                    \DB::table('users')
                    ->where('id', $coach->id)
                    ->update(['embedding' => json_encode($embedding)]);
                    $processed++;
                    // echo "✓ {$coach->first_name} {$coach->last_name}\n";
                    // Log the dimensions instead of echoing
                    // \Log::info('Coach embedding generated', [
                    //     'coach_id' => $coach->id,
                    //     'name' => $coach->first_name . ' ' . $coach->last_name,
                    //     'dimensions' => count($embedding),
                    //     'profile_text_length' => strlen($profileText),
                    // ]);
                    echo "✓ {$coach->first_name} {$coach->last_name} (dims: " . count($embedding) . ")\n";
                } else {
                    $failed++;
                    // \Log::warning('Coach embedding failed (empty text?)', [
                    //     'coach_id' => $coach->id,
                    //     'name' => $coach->first_name . ' ' . $coach->last_name,
                    // ]);
                    echo "✗ {$coach->first_name} {$coach->last_name} (failed)\n";
                }

                usleep(200000); // Rate limiting
            } catch (\Exception $e) {
                $failed++;
                Log::error("Failed for coach {$coach->id}: " . $e->getMessage());
            }
        }

        return compact('processed', 'failed');
    }

    /**
     * Build coach profile text for embedding
     */
    public function buildCoachProfileText($coach)
    {
        $parts = array_filter([
            $coach->professional_title,
            $coach->short_bio,
            $coach->coaching_topics,
            $coach->coaching_goal_1,
            $coach->coaching_goal_2,
            $coach->coaching_goal_3,
            $coach->exp_and_achievement,
        ]);

        return implode('. ', $parts);
    }






    /**
     * Generate embedding using OpenAI
     */
    // public function generateEmbedding(string $text): ?array
    // {
    //     // 1️⃣ Safety check (VERY IMPORTANT)
    //     $text = trim($text);
    //     if (strlen($text) < 10) {
    //         Log::warning('Embedding skipped: text too short', ['text' => $text]);
    //         return null;
    //     }

    //     // 2️⃣ Cache key
    //     $cacheKey = 'embedding_' . md5($text);

    //     if (Cache::has($cacheKey)) {
    //         return Cache::get($cacheKey);
    //     }

    //     try {
    //         // 3️⃣ API call (cleaner withToken usage)
    //         $response = Http::withToken(config('services.openai.key'))
    //             ->timeout(15)
    //             ->post('https://api.openai.com/v1/embeddings', [
    //                 'model' => 'text-embedding-3-small',
    //                 'input' => $text,
    //             ]);

    //         // 4️⃣ Success case
    //         if ($response->successful()) {
    //             $embedding = $response->json('data.0.embedding');
    //             Cache::put($cacheKey, $embedding, now()->addDays(30));
    //             return $embedding;
    //         }

    //         // 5️⃣ CRITICAL: log API error response
    //         Log::error('OpenAI Embedding API Error', [
    //             'status' => $response->status(),
    //             'response' => $response->json() ?? $response->body(),
    //         ]);

    //         return null;

    //     } catch (\Throwable $e) {
    //         // 6️⃣ Network / timeout / unexpected errors
    //         Log::error('OpenAI Embedding Exception', [
    //             'message' => $e->getMessage(),
    //         ]);
    //         return null;
    //     }
    // }


    // private function calculateModeScore($userMode, $coachDeliveryMode)
    // {
    //     if ($userMode === 'No preference') {
    //         return 5;
    //     }

    //     $modeMap = [
    //         1 => 'online',
    //         2 => 'In-person',
    //         3 => 'hybrid'
    //     ];

    //     $coachMode = $modeMap[$coachDeliveryMode] ?? 'online';
    //     $userModeLower = strtolower($userMode);

    //     if ($coachMode === $userModeLower) {
    //         return 10;
    //     } elseif ($coachMode === 'hybrid') {
    //         return 8;
    //     }

    //     return 0;
    // }

    // ✅ RECOMMENDED Improved Version (Clean & Fair)
    // Step 1: Merge all coach topics & goals
    // $topics = [];

    // if (!empty($coach->coaching_topics)) {
    //     $topics = array_merge(
    //         $topics,
    //         explode(',', $coach->coaching_topics)
    //     );
    // }

    // foreach (['coaching_goal_1', 'coaching_goal_2', 'coaching_goal_3'] as $goalField) {
    //     if (!empty($coach->$goalField)) {
    //         $topics[] = $coach->$goalField;
    //     }
    // }

    // Step 2: Improved Scoring Logic (Topic-based, not word-spam)
    // private function calculateTopicsScore($userGoals, array $coachTopics)
    // {
    //     if (empty($coachTopics)) {
    //         return 0;
    //     }

    //     $userText = strtolower($userGoals);
    //     $matchedTopics = 0;

    //     foreach ($coachTopics as $topic) {
    //         $topic = strtolower(trim($topic));

    //         if ($topic && str_contains($userText, $topic)) {
    //             $matchedTopics++;
    //         }
    //     }

    //     // Each matched topic = 3 points
    //     $score = $matchedTopics * 3;

    //     return min(10, $score);
    // }

    // 🎯 Why This Is Better

    // ✔ Matches topics, not random words
    // ✔ Avoids over-scoring
    // ✔ Uses your existing DB structure fully
    // ✔ Predictable scoring:

    // 1 topic match → 3 points

    // 2 topic match → 6 points

    // 3+ topic match → 9–10 points

}