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

        // Check if level is unlocked
        $progress = UserProgress::where('user_id', $user->id)
                                ->where('level_id', $level->id)
                                ->first();

        if (!$progress || !$progress->is_unlocked) {
            return response()->json([
                'success' => false,
                'message' => 'Level is locked'
            ], 403);
        }

        // Check if level is premium and user has enough coins
        if ($level->is_premium && !$progress->is_unlocked) {
            if ($user->coins < $level->coin_price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough coins'
                ], 403);
            }
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

        return response()->json([
            'success' => true,
            'data' => [
                'question_id' => $question->id,
                'level_number' => $level->level_number,
                'image_url' => $question->image_url,
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

        // Check answer
        if ($question->checkAnswer($request->answer)) {
            // Correct answer
            DB::transaction(function() use ($user, $question, $level, $progress) {
                // Mark level as completed
                if (!$progress->is_completed) {
                    $progress->markAsCompleted();
                    
                    // Add score
                    $user->addScore($question->points);
                    
                    // Update leaderboard
                    $leaderboard = Leaderboard::where('user_id', $user->id)->first();
                    if ($leaderboard) {
                        $completedLevels = UserProgress::where('user_id', $user->id)
                                                       ->where('is_completed', true)
                                                       ->count();
                        $leaderboard->updateScore($user->total_score, $completedLevels);
                    }
                    
                    // Give reward coins if level 10 completed
                    if ($level->level_number == 10) {
                        $user->addCoins($level->reward_coins);
                    }
                    
                    // Unlock next level
                    $nextLevel = Level::where('level_number', $level->level_number + 1)->first();
                    if ($nextLevel) {
                        UserProgress::updateOrCreate(
                            ['user_id' => $user->id, 'level_id' => $nextLevel->id],
                            ['is_unlocked' => true]
                        );
                        $user->unlockNextLevel();
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
            // Wrong answer - deduct heart
            $user->useHeart();

            if ($user->hearts <= 0) {
                // Reset to level 1
                $user->current_level = 1;
                $user->resetHearts();
                $user->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Game over! Hearts depleted. Reset to level 1.',
                    'data' => [
                        'is_correct' => false,
                        'hearts' => $user->hearts,
                        'game_over' => true,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Wrong answer!',
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
        $level = Level::where('level_number', $levelNumber)->where('is_premium', true)->first();

        if (!$level) {
            return response()->json([
                'success' => false,
                'message' => 'Premium level not found'
            ], 404);
        }

        if ($user->coins < $level->coin_price) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough coins'
            ], 403);
        }

        // Deduct coins
        $user->deductCoins($level->coin_price);

        // Unlock level
        UserProgress::updateOrCreate(
            ['user_id' => $user->id, 'level_id' => $level->id],
            ['is_unlocked' => true]
        );

        return response()->json([
            'success' => true,
            'message' => 'Premium level unlocked!',
            'data' => [
                'remaining_coins' => $user->coins,
            ]
        ]);
    }
}