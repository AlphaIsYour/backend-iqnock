<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        
        // Game
        Route::get('/levels', [GameController::class, 'getLevels']);
        Route::get('/levels/{levelNumber}/question', [GameController::class, 'getQuestion']);
        Route::post('/answer', [GameController::class, 'submitAnswer']);
        Route::post('/hint', [GameController::class, 'useHint']);
        Route::post('/levels/{levelNumber}/unlock', [GameController::class, 'unlockPremiumLevel']);
        
        // Leaderboard
        Route::get('/leaderboard', [LeaderboardController::class, 'index']);
        Route::get('/leaderboard/my-rank', [LeaderboardController::class, 'myRank']);
        
        // Feedback
        Route::post('/feedback', [FeedbackController::class, 'store']);
        Route::get('/feedback/my', [FeedbackController::class, 'myFeedback']);
    });
});