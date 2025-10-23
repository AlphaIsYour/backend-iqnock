<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\LeaderboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });
    
    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Users Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-progress', [UserController::class, 'resetProgress'])->name('users.reset-progress');
        
        // Levels Management
        Route::resource('levels', LevelController::class);
        
        // Questions Management
        Route::resource('questions', QuestionController::class);
        
        // Feedback Management
        Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
        Route::put('feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
        Route::delete('feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
        
        // Leaderboard Management
        Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
        Route::post('leaderboard/update-rankings', [LeaderboardController::class, 'updateRankings'])->name('leaderboard.update-rankings');
        Route::post('leaderboard/{leaderboard}/reset', [LeaderboardController::class, 'reset'])->name('leaderboard.reset');
    });
});