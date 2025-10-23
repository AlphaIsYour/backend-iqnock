<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Leaderboard::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $leaderboards = $query->orderBy('rank')->paginate(50);

        return view('admin.leaderboard.index', compact('leaderboards'));
    }

    public function updateRankings()
    {
        Leaderboard::updateRankings();
        return back()->with('success', 'Rankings updated successfully');
    }

    public function reset(Leaderboard $leaderboard)
    {
        $leaderboard->update([
            'total_score' => 0,
            'levels_completed' => 0,
        ]);

        Leaderboard::updateRankings();

        return back()->with('success', 'Leaderboard entry reset successfully');
    }
}