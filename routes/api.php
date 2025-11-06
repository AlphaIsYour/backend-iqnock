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
        Route::put('/profile/update', [AuthController::class, 'updateProfile']);
        
        // Game
        Route::get('/levels', [GameController::class, 'getLevels']);
        Route::get('/levels/{levelNumber}/question', [GameController::class, 'getQuestion']);
        Route::post('/answer', [GameController::class, 'submitAnswer']);
        Route::post('/hint', [GameController::class, 'useHint']);
        Route::post('/levels/{levelNumber}/unlock', [GameController::class, 'unlockPremiumLevel']);
        
        // Leaderboard
        Route::get('/leaderboard', [LeaderboardController::class, 'index']);
        Route::get('/leaderboard/my-rank', [LeaderboardController::class, 'myRank']);
        Route::post('/feedback', [FeedbackController::class, 'store']);
        Route::get('/feedback/my', [FeedbackController::class, 'myFeedback']);
        Route::post('/questions/submit', [FeedbackController::class, 'submitQuestion']);
        
        // Feedback
        Route::post('/feedback', [FeedbackController::class, 'store']);
        Route::get('/feedback/my', [FeedbackController::class, 'myFeedback']);
    });
});

Route::get('/storage/{path}', function ($path) {
    $file = storage_path('app/public/' . $path);
    
    if (!file_exists($file)) {
        abort(404);
    }
    
    return response()->file($file, [
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*');