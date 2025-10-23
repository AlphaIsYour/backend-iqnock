<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Level;
use App\Models\Question;
use App\Models\Feedback;
use App\Models\Leaderboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_levels' => Level::count(),
            'total_questions' => Question::count(),
            'pending_feedback' => Feedback::where('status', 'pending')->count(),
            'total_feedback' => Feedback::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $topPlayers = Leaderboard::with('user')->orderBy('rank')->take(10)->get();
        $recentFeedback = Feedback::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'topPlayers', 'recentFeedback'));
    }
}