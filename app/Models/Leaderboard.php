<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $table = 'leaderboard';

    protected $fillable = [
        'user_id',
        'total_score',
        'levels_completed',
        'rank',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public static function updateRankings()
    {
        $leaderboards = self::orderBy('total_score', 'desc')
                            ->orderBy('levels_completed', 'desc')
                            ->get();
        
        $rank = 1;
        foreach ($leaderboards as $leaderboard) {
            $leaderboard->rank = $rank;
            $leaderboard->save();
            $rank++;
        }
    }

    public function updateScore(int $score, int $levelsCompleted)
    {
        $this->total_score = $score;
        $this->levels_completed = $levelsCompleted;
        $this->save();
        
        // Update rankings
        self::updateRankings();
    }
}