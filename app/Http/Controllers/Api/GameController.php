<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Question;
use App\Models\UserProgress;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function getLevels(Request $request)
    {
        $user = $request->user();
        $levels = Level::with(['userProgress' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->where('is_active', true)->orderBy('level_number')->get();

        $levelsData = $levels->map(function($level) use ($user) {
            $progress = $level->userProgress->first();
            
            return [
                'id' => $level->id,
                'level_number' => $level->level_number,
                'level_name' => $level->level_name,
                'is_premium' => $level->is_premium,
                'coin_price' => $level->coin_price,
                'reward_coins' => $level->reward_coins,
                'is_unlocked' => $progress ? $progress->is_unlocked : false,
                'is_completed' => $progress ? $progress->is_completed : false,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'levels' => $levelsData,
                'user_stats' => [
                    'coins' => $user->coins,
                    'hearts' => $user->hearts,
                    'hints' => $user->hints,
                    'current_level' => $user->current_level,
                ]
            ]
        ]);
    }

    public function getQuestion(Request $request, $levelNumber)
    {
        $user = $request->user();
        $level = Level::where('level_number', $levelNumber)->first();

        if (!$level) {
            return response()->json([
                'success' => false,
                'message' => 'Level not found'
            ], 404);
        }

        // Check progress
        $progress = UserProgress::where('user_id', $user->id)
                                ->where('level_id', $level->id)
                                ->first();

        // Cek apakah level premium sudah dibeli (is_unlocked = purchased)
        if ($level->is_premium) {
            if (!$progress || !$progress->is_unlocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please purchase this premium level first',
                    'coin_price' => $level->coin_price
                ], 403);
            }
        }

        // Check if level is unlocked
        if (!$progress || !$progress->is_unlocked) {
            return response()->json([
                'success' => false,
                'message' => 'Level is locked'
            ], 403);
        }

        $question = Question::where('level_id', $level->id)
                           ->where('is_active', true)
                           ->inRandomOrder()
                           ->first();

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'No question found for this level'
            ], 404);
        }

        $filename = basename($question->image_url);
        $imagePath = storage_path('app/public/questions/' . $filename);

        if (!file_exists($imagePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        $imageData = base64_encode(file_get_contents($imagePath));
        $imageMime = mime_content_type($imagePath);

        return response()->json([
            'success' => true,
            'data' => [
                'question_id' => $question->id,
                'level_number' => $level->level_number,
                'image_data' => 'data:' . $imageMime . ';base64,' . $imageData,
                'points' => $question->points,
                'user_stats' => [
                    'hearts' => $user->hearts,
                    'hints' => $user->hints,
                ]
            ]
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
        ]);

        $user = $request->user();
        $question = Question::findOrFail($request->question_id);
        $level = $question->level;

        $progress = UserProgress::where('user_id', $user->id)
                                ->where('level_id', $level->id)
                                ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'Progress not found'
            ], 404);
        }

        $progress->incrementAttempt();

        if ($question->checkAnswer($request->answer)) {
            DB::transaction(function() use ($user, $question, $level, $progress) {
                if (!$progress->is_completed) {
                    $progress->markAsCompleted();
                    $user->addScore($question->points);
                    
                    $leaderboard = Leaderboard::where('user_id', $user->id)->first();
                    if ($leaderboard) {
                        $completedLevels = UserProgress::where('user_id', $user->id)
                                                       ->where('is_completed', true)
                                                       ->count();
                        $leaderboard->updateScore($user->total_score, $completedLevels);
                    }
                    
                    // Berikan reward coins di setiap akhir grup (level 10, 20, 30, dst)
                    if ($level->level_number % 10 == 0) {
                        $user->addCoins($level->reward_coins);
                    }
                    
                    // Unlock next level (baik free maupun premium yang sudah dibeli)
                    $nextLevel = Level::where('level_number', $level->level_number + 1)->first();
                    if ($nextLevel) {
                        // Cek apakah next level adalah premium
                        if ($nextLevel->is_premium) {
                            // Cek apakah grup premium sudah dibeli
                            // Dengan cara cek apakah level pertama di grup sudah unlock
                            $nextGroupStart = (int)(($nextLevel->level_number - 1) / 10) * 10 + 1;
                            $firstLevelInNextGroup = Level::where('level_number', $nextGroupStart)->first();
                            
                            $firstProgress = UserProgress::where('user_id', $user->id)
                                                         ->where('level_id', $firstLevelInNextGroup->id)
                                                         ->first();
                            
                            // Jika grup premium sudah dibeli (level pertama unlocked), unlock level berikutnya
                            if ($firstProgress && $firstProgress->is_unlocked) {
                                UserProgress::updateOrCreate(
                                    ['user_id' => $user->id, 'level_id' => $nextLevel->id],
                                    ['is_unlocked' => true]
                                );
                                $user->unlockNextLevel();
                            }
                        } else {
                            // Free level, unlock langsung
                            UserProgress::updateOrCreate(
                                ['user_id' => $user->id, 'level_id' => $nextLevel->id],
                                ['is_unlocked' => true]
                            );
                            $user->unlockNextLevel();
                        }
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Correct answer!',
                'data' => [
                    'is_correct' => true,
                    'points_earned' => $question->points,
                    'total_score' => $user->total_score,
                    'coins' => $user->coins,
                    'next_level_unlocked' => $level->level_number + 1,
                ]
            ]);
        } else {
            $user->useHeart();

            if ($user->hearts <= 0) {
                $user->current_level = 1;
                $user->resetHearts();
                $user->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Game over!',
                    'data' => [
                        'is_correct' => false,
                        'hearts' => $user->hearts,
                        'game_over' => true,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Salah!',
                'data' => [
                    'is_correct' => false,
                    'hearts' => $user->hearts,
                ]
            ]);
        }
    }

    public function useHint(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
        ]);

        $user = $request->user();
        
        if ($user->hints <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No hints available'
            ], 403);
        }

        $question = Question::findOrFail($request->question_id);
        $user->useHint();

        return response()->json([
            'success' => true,
            'data' => [
                'hint' => $question->getHint(),
                'remaining_hints' => $user->hints,
            ]
        ]);
    }

    public function unlockPremiumLevel(Request $request, $levelNumber)
    {
        $user = $request->user();
        $level = Level::where('level_number', $levelNumber)
                     ->where('is_premium', true)
                     ->first();

        if (!$level) {
            return response()->json([
                'success' => false,
                'message' => 'Level not found'
            ], 404);
        }

        // Hitung grup level (11-20, 21-30, dst)
        $groupStart = (int)(($level->level_number - 1) / 10) * 10 + 1;
        $groupEnd = $groupStart + 9;

        // Cek apakah grup ini sudah dibeli
        $firstLevelInGroup = Level::where('level_number', $groupStart)->first();
        $firstProgress = UserProgress::where('user_id', $user->id)
                                     ->where('level_id', $firstLevelInGroup->id)
                                     ->first();

        if ($firstProgress && $firstProgress->is_unlocked) {
            return response()->json([
                'success' => false,
                'message' => 'Level sudah dibeli'
            ], 400);
        }

        if ($user->coins < $level->coin_price) {
            return response()->json([
                'success' => false,
                'message' => 'Coin tidak cukup',
                'required_coins' => $level->coin_price,
                'current_coins' => $user->coins
            ], 403);
        }

        DB::transaction(function() use ($user, $level, $groupStart) {
            // Deduct coins
            $user->deductCoins($level->coin_price);

            // Unlock HANYA level pertama dalam grup (level 11, 21, 31, dst)
            $firstLevel = Level::where('level_number', $groupStart)->first();
            
            UserProgress::updateOrCreate(
                ['user_id' => $user->id, 'level_id' => $firstLevel->id],
                ['is_unlocked' => true]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Level berhasil dibeli! Silahkan selesaikan level.',
            'data' => [
                'remaining_coins' => $user->coins,
                'first_unlocked_level' => $groupStart
            ]
        ]);
    }
}