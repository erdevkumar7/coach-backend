<?php

namespace App\Console\Commands;

use App\Services\CoachMatchingService;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateCoachEmbeddings extends Command
{
    protected $signature = 'coaches:generate-embeddings
                          {--limit=100 : Maximum number of coaches to process}
                          {--force : Regenerate embeddings even if they exist}';

    protected $description = 'Generate AI embeddings for coach profiles using OpenAI API';

    protected $matchingService;

    public function __construct(CoachMatchingService $matchingService)
    {
        parent::__construct();
        $this->matchingService = $matchingService;
    }

    public function handle()
    {
        $this->info('🚀 Starting AI Embedding Generation for Coaches');
        $this->info('Using OpenAI API (text-embedding-3-small model)');
        $this->newLine();

        // Check if OpenAI key is configured
        if (!config('services.openai.key')) {
            $this->error('❌ OpenAI API key not found!');
            $this->error('Please add OPENAI_API_KEY to your .env file');
            return 1;
        }

        $limit = $this->option('limit');
        $force = $this->option('force');

        // Get coaches that need embeddings
        $query = User::where('user_type', 3)
            ->where('user_status', 1)
            ->where('is_deleted', 0);

        if (!$force) {
            $query->whereNull('embedding');
        }

        $coaches = $query->limit($limit)->get();
        $totalCoaches = $coaches->count();

        if ($totalCoaches === 0) {
            $this->info('✅ No coaches found that need embeddings!');
            $this->info('All coaches already have embeddings generated.');
            return 0;
        }

        $this->info("📊 Found {$totalCoaches} coaches to process");
        $this->newLine();

        // Progress bar
        $bar = $this->output->createProgressBar($totalCoaches);
        $bar->start();

        $processed = 0;
        $failed = 0;
        $failedCoaches = [];

        foreach ($coaches as $coach) {
            try {
                $profileText = $this->buildCoachProfileText($coach);

                if (empty(trim($profileText))) {
                    $this->newLine();
                    $this->warn("⚠️  Skipping {$coach->first_name} {$coach->last_name} - No profile data");
                    // Log::warning('Coach skipped - empty profile', [
                    //     'coach_id' => $coach->id,
                    //     'name' => $coach->first_name . ' ' . $coach->last_name,
                    // ]);
                    $failed++;
                    $failedCoaches[] = [
                        'id' => $coach->id,
                        'name' => "{$coach->first_name} {$coach->last_name}",
                        'reason' => 'Empty profile'
                    ];
                    $bar->advance();
                    continue;
                }

                $embedding = $this->matchingService->generateEmbedding($profileText);

                if ($embedding) {
                    // $coach->update(['embedding' => json_encode($embedding)]);
                    $coach->embedding = json_encode($embedding);
                    $coach->save();
                    $processed++;

                    // LOG DIMENSIONS HERE
                    // Log::info('Coach embedding generated successfully', [
                    //     'coach_id' => $coach->id,
                    //     'name' => $coach->first_name . ' ' . $coach->last_name,
                    //     'dimensions' => count($embedding),
                    //     'profile_text_length' => strlen($profileText),
                    // ]);

                    // Simple console feedback
                    $this->line(" <info>✓</info> {$coach->first_name} {$coach->last_name} (dims: " . count($embedding) . ")");
                } else {
                    $failed++;
                    $failedCoaches[] = [
                        'id' => $coach->id,
                        'name' => "{$coach->first_name} {$coach->last_name}",
                        'reason' => 'API request failed (null response)'
                    ];
                    // Log::error('Embedding generation failed - null response', [
                    //     'coach_id' => $coach->id,
                    // ]);
                }

                $bar->advance();

                // Rate limiting
                usleep(200000); // 0.2 seconds

            } catch (\Exception $e) {
                $failed++;
                $failedCoaches[] = [
                    'id' => $coach->id,
                    'name' => "{$coach->first_name} {$coach->last_name}",
                    'reason' => $e->getMessage()
                ];
                // Log::error('Exception during embedding generation', [
                //     'coach_id' => $coach->id,
                //     'error' => $e->getMessage(),
                // ]);
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('📊 EMBEDDING GENERATION SUMMARY');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info("✅ Successfully processed: {$processed}");

        if ($failed > 0) {
            $this->error("❌ Failed: {$failed}");
            $this->newLine();
            $this->error('Failed Coaches:');
            foreach ($failedCoaches as $fc) {
                $this->error("  • ID {$fc['id']}: {$fc['name']} - {$fc['reason']}");
            }
        }

        $this->info("📈 Total coaches processed: {$totalCoaches}");
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        if ($processed > 0) {
            $this->info('🎉 Embeddings generated successfully!');
            $this->info('Your AI matching system is now ready to use.');
            $this->info('Check storage/logs/laravel.log for detailed dimension logs.');
        }

        return 0;
    }

    /**
     * Build coach profile text for embedding
     */
    private function buildCoachProfileText($coach)
    {
        $parts = array_filter([
            $coach->professional_title,
            $coach->short_bio,
            $coach->detailed_bio,        // Added this (was missing in your original)
            $coach->coaching_topics,
            $coach->coaching_goal_1,
            $coach->coaching_goal_2,
            $coach->coaching_goal_3,
            $coach->exp_and_achievement,
        ]);

        return implode('. ', $parts);
    }
}





// namespace App\Console\Commands;

// use App\Services\CoachMatchingService;
// use App\Models\User;
// use Illuminate\Console\Command;

// class GenerateCoachEmbeddings extends Command
// {
//     protected $signature = 'coaches:generate-embeddings
//                           {--limit=100 : Maximum number of coaches to process}
//                           {--force : Regenerate embeddings even if they exist}';

//     protected $description = 'Generate AI embeddings for coach profiles using OpenAI API';

//     protected $matchingService;

//     public function __construct(CoachMatchingService $matchingService)
//     {
//         parent::__construct();
//         $this->matchingService = $matchingService;
//     }

//     public function handle()
//     {
//         $this->info('🚀 Starting AI Embedding Generation for Coaches');
//         $this->info('Using OpenAI API (text-embedding-3-small model)');
//         $this->newLine();

//         // Check if OpenAI key is configured
//         if (!config('services.openai.key')) {
//             $this->error('❌ OpenAI API key not found!');
//             $this->error('Please add OPENAI_API_KEY to your .env file');
//             return 1;
//         }

//         $limit = $this->option('limit');
//         $force = $this->option('force');

//         // Get coaches that need embeddings
//         $query = User::where('user_type', 3)
//             ->where('user_status', 1)
//             ->where('is_deleted', 0);

//         if (!$force) {
//             $query->whereNull('embedding');
//         }

//         $coaches = $query->limit($limit)->get();
//         $totalCoaches = $coaches->count();

//         if ($totalCoaches === 0) {
//             $this->info('✅ No coaches found that need embeddings!');
//             $this->info('All coaches already have embeddings generated.');
//             return 0;
//         }

//         $this->info("📊 Found {$totalCoaches} coaches to process");
//         $this->newLine();

//         // Progress bar
//         $bar = $this->output->createProgressBar($totalCoaches);
//         $bar->start();

//         $processed = 0;
//         $failed = 0;
//         $failedCoaches = [];

//         foreach ($coaches as $coach) {
//             try {
//                 $profileText = $this->buildCoachProfileText($coach);

//                 if (empty(trim($profileText))) {
//                     $this->newLine();
//                     $this->warn("⚠️  Skipping {$coach->first_name} {$coach->last_name} - No profile data");
//                     $failed++;
//                     $failedCoaches[] = [
//                         'id' => $coach->id,
//                         'name' => "{$coach->first_name} {$coach->last_name}",
//                         'reason' => 'Empty profile'
//                     ];
//                     $bar->advance();
//                     continue;
//                 }

//                 $embedding = $this->matchingService->generateEmbedding($profileText);

//                 if ($embedding) {
//                     $coach->update(['embedding' => json_encode($embedding)]);
//                     $processed++;
//                 } else {
//                     $failed++;
//                     $failedCoaches[] = [
//                         'id' => $coach->id,
//                         'name' => "{$coach->first_name} {$coach->last_name}",
//                         'reason' => 'API request failed'
//                     ];
//                 }

//                 $bar->advance();

//                 // Rate limiting: OpenAI allows 3,000 requests/min
//                 // Add small delay to be safe
//                 usleep(200000); // 200ms = 5 requests per second = 300/min

//             } catch (\Exception $e) {
//                 $failed++;
//                 $failedCoaches[] = [
//                     'id' => $coach->id,
//                     'name' => "{$coach->first_name} {$coach->last_name}",
//                     'reason' => $e->getMessage()
//                 ];
//                 $bar->advance();
//             }
//         }

//         $bar->finish();
//         $this->newLine(2);

//         // Summary
//         $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
//         $this->info('📊 EMBEDDING GENERATION SUMMARY');
//         $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
//         $this->info("✅ Successfully processed: {$processed}");

//         if ($failed > 0) {
//             $this->error("❌ Failed: {$failed}");
//             $this->newLine();
//             $this->error('Failed Coaches:');
//             foreach ($failedCoaches as $fc) {
//                 $this->error("  • ID {$fc['id']}: {$fc['name']} - {$fc['reason']}");
//             }
//         }

//         $this->info("📈 Total coaches: {$totalCoaches}");
//         $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
//         $this->newLine();

//         if ($processed > 0) {
//             $this->info('🎉 Embeddings generated successfully!');
//             $this->info('Your AI matching system is now ready to use.');
//         }

//         return 0;
//     }

//     /**
//      * Build coach profile text for embedding
//      */
//     private function buildCoachProfileText($coach)
//     {
//         $parts = array_filter([
//             $coach->professional_title,
//             $coach->short_bio,
//             $coach->detailed_bio,
//             $coach->coaching_topics,
//             $coach->coaching_goal_1,
//             $coach->coaching_goal_2,
//             $coach->coaching_goal_3,
//             $coach->exp_and_achievement,
//         ]);

//         return implode('. ', $parts);
//     }
// }