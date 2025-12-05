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
            
            // ✅ BEST PRACTICE: Tentukan unlock logic dengan jelas
            $isUnlocked = $this->determineUnlockStatus($level, $progress, $user);
            
            return [
                'id' => $level->id,
                'level_number' => $level->level_number,
                'level_name' => $level->level_name,
                'is_premium' => $level->is_premium,
                'coin_price' => $level->coin_price,
                'reward_coins' => $level->reward_coins,
                'is_unlocked' => $isUnlocked,
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

    /**
     * ✅ BEST PRACTICE: Pisahkan logic unlock ke method terpisah
     * Ini bikin code lebih clean, testable, dan mudah di-maintain
     */
    private function determineUnlockStatus($level, $progress, $user)
    {
        // 1. Kalau ada progress, pakai status dari database
        if ($progress) {
            return $progress->is_unlocked;
        }
        
        // 2. Kalau belum ada progress, tentukan berdasarkan tipe level
        if ($level->is_premium) {
            // Premium level: default locked
            return false;
        }
        
        // 3. Free level: cek apakah ini level awal grup (1, 11, 21, dst)
        // Level awal grup selalu unlock by default
        $groupStart = (int)(($level->level_number - 1) / 10) * 10 + 1;
        
        if ($level->level_number == $groupStart) {
            return true;
        }
        
        // 4. Free level non-awal grup: unlock kalau user sudah sampai level ini
        return $level->level_number <= $user->current_level;
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

        // ✅ BEST PRACTICE: Auto-create progress untuk free level yang belum ada
        if (!$progress && !$level->is_premium) {
            $groupStart = (int)(($level->level_number - 1) / 10) * 10 + 1;
            
            // Auto unlock kalau ini level awal grup atau user sudah sampai level ini
            if ($level->level_number == $groupStart || $level->level_number <= $user->current_level) {
                $progress = UserProgress::create([
                    'user_id' => $user->id,
                    'level_id' => $level->id,
                    'is_unlocked' => true,
                    'is_completed' => false,
                    'attempts' => 0,
                ]);
            }
        }

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

        // ✅ FIX: Return URL Cloudinary langsung, BUKAN base64 lagi!
        return response()->json([
            'success' => true,
            'data' => [
                'question_id' => $question->id,
                'level_number' => $level->level_number,
                'image_url' => $question->image_url, // 👈 Langsung return URL Cloudinary
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
                    $user->addCoins($level->reward_coins);
                    
                    // ✅ BEST PRACTICE: Pisahkan unlock next level ke method terpisah
                    $this->unlockNextLevel($user, $level);
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
                // ✅ BEST PRACTICE: Pisahkan reset logic ke method terpisah
                $this->resetCurrentGroup($user, $level);

                return response()->json([
                    'success' => false,
                    'message' => 'Game over!',
                    'data' => [
                        'is_correct' => false,
                        'hearts' => $user->hearts,
                        'game_over' => true,
                        'reset_to_level' => $user->current_level,
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

    /**
     * ✅ BEST PRACTICE: Method terpisah untuk unlock next level
     * Memudahkan maintenance dan testing
     */
    private function unlockNextLevel($user, $currentLevel)
    {
        $nextLevel = Level::where('level_number', $currentLevel->level_number + 1)->first();
        
        if (!$nextLevel) {
            return; // Sudah level terakhir
        }

        // Cek apakah next level adalah premium
        if ($nextLevel->is_premium) {
            // Cek apakah grup premium sudah dibeli
            $nextGroupStart = (int)(($nextLevel->level_number - 1) / 10) * 10 + 1;
            $firstLevelInNextGroup = Level::where('level_number', $nextGroupStart)->first();
            
            $firstProgress = UserProgress::where('user_id', $user->id)
                                         ->where('level_id', $firstLevelInNextGroup->id)
                                         ->first();
            
            // Jika grup premium sudah dibeli, unlock level berikutnya
            if ($firstProgress && $firstProgress->is_unlocked) {
                UserProgress::updateOrCreate(
                    ['user_id' => $user->id, 'level_id' => $nextLevel->id],
                    ['is_unlocked' => true, 'is_completed' => false, 'attempts' => 0]
                );
                $user->unlockNextLevel();
                $user->updateHighestLevel();
            }
        } else {
            // Free level, unlock langsung
            UserProgress::updateOrCreate(
                ['user_id' => $user->id, 'level_id' => $nextLevel->id],
                ['is_unlocked' => true, 'is_completed' => false, 'attempts' => 0]
            );
            $user->unlockNextLevel();
            $user->updateHighestLevel();
        }
    }

    /**
     * ✅ BEST PRACTICE: Method terpisah untuk reset grup
     */
    private function resetCurrentGroup($user, $level)
    {
        $currentGroupStart = (int)(($level->level_number - 1) / 10) * 10 + 1;
        $currentGroupEnd = $currentGroupStart + 9;
        
        // Reset progress di grup ini
        $levels = Level::whereBetween('level_number', [$currentGroupStart, $currentGroupEnd])->get();
        
        foreach ($levels as $lvl) {
            $prog = UserProgress::where('user_id', $user->id)
                               ->where('level_id', $lvl->id)
                               ->first();
            
            if ($prog) {
                if ($lvl->level_number == $currentGroupStart) {
                    // Level pertama grup tetap unlock tapi reset completed
                    $prog->update([
                        'is_completed' => false,
                        'attempts' => 0,
                    ]);
                } else {
                    // Level lain di-lock
                    $prog->update([
                        'is_unlocked' => false,
                        'is_completed' => false,
                        'attempts' => 0,
                    ]);
                }
            }
        }
        
        $user->current_level = $currentGroupStart;
        $user->resetHearts();
        $user->hints = 5;
        $user->save();
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

    public function resetGroupLevels(Request $request)
    {
        $request->validate([
            'group_start' => 'required|integer',
            'group_end' => 'required|integer',
        ]);

        $user = $request->user();
        $groupStart = $request->group_start;
        $groupEnd = $request->group_end;

        DB::transaction(function() use ($user, $groupStart, $groupEnd) {
            // Get all levels in the group
            $levels = Level::whereBetween('level_number', [$groupStart, $groupEnd])->get();

            foreach ($levels as $level) {
                $progress = UserProgress::where('user_id', $user->id)
                                       ->where('level_id', $level->id)
                                       ->first();

                if ($progress) {
                    // Reset semua level KECUALI level pertama grup (1, 11, 21, dst)
                    if ($level->level_number == $groupStart) {
                        // Level pertama tetap unlocked tapi jadi not completed
                        $progress->update([
                            'is_unlocked' => true,
                            'is_completed' => false,
                            'attempts' => 0,
                        ]);
                    } else {
                        // Level lainnya di-lock
                        $progress->update([
                            'is_unlocked' => false,
                            'is_completed' => false,
                            'attempts' => 0,
                        ]);
                    }
                }
            }

            // Reset hearts dan hints
            $user->hearts = 5;
            $user->hints = 5;
            $user->current_level = $groupStart;
            $user->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Group levels reset successfully',
            'data' => [
                'hearts' => $user->hearts,
                'hints' => $user->hints,
                'current_level' => $user->current_level,
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
                ['is_unlocked' => true, 'is_completed' => false, 'attempts' => 0]
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