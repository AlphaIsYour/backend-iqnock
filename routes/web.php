<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\LeaderboardController;
use Illuminate\Support\Facades\File;  
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ImageController;

Route::post('/upload', [ImageController::class, 'upload']);
Route::post('/upload-optimized', [ImageController::class, 'uploadOptimized']);
Route::post('/upload-thumbnail', [ImageController::class, 'uploadThumbnail']);
Route::delete('/delete', [ImageController::class, 'delete']);
Route::post('/update', [ImageController::class, 'update']);
Route::post('/responsive-urls', [ImageController::class, 'getResponsiveUrls']);
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

Route::get('/storage/questions/{filename}', function ($filename) {
    $file = storage_path('app/public/questions/' . $filename);
    
    if (!file_exists($file)) {
        abort(404);
    }
    
    $response = response()->file($file);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET');
    $response->headers->set('Access-Control-Allow-Headers', '*');
    
    return $response;
});

// Route untuk mengambil file dari storage/app/public
Route::get('my-storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path("app/public/{$folder}/{$filename}");

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('folder', '.*'); // Memastikan folder bisa mengandung sub-folder