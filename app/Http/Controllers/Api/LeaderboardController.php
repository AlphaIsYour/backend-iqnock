<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $leaderboard = Leaderboard::with('user:id,name')
                                  ->orderBy('rank')
                                  ->take(100)
                                  ->get();

        return response()->json([
            'success' => true,
            'data' => $leaderboard->map(function($entry) {
                return [
                    'rank' => $entry->rank,
                    'user_name' => $entry->user->name,
                    'total_score' => $entry->total_score,
                    'levels_completed' => $entry->levels_completed,
                ];
            })
        ]);
    }

    public function myRank(Request $request)
    {
        $user = $request->user();
        $leaderboard = Leaderboard::where('user_id', $user->id)->first();

        if (!$leaderboard) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard entry not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'rank' => $leaderboard->rank,
                'total_score' => $leaderboard->total_score,
                'levels_completed' => $leaderboard->levels_completed,
            ]
        ]);
    }
}