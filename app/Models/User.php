<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'coins',
        'hearts',
        'hints',
        'current_level',
        'total_score',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    // Helper Methods
    public function unlockNextLevel()
    {
        $this->current_level += 1;
        $this->save();
    }

    public function addCoins(int $amount)
    {
        $this->coins += $amount;
        $this->save();
    }

    public function deductCoins(int $amount)
    {
        if ($this->coins >= $amount) {
            $this->coins -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    public function useHeart()
    {
        if ($this->hearts > 0) {
            $this->hearts -= 1;
            $this->save();
            return true;
        }
        return false;
    }

    public function useHint()
    {
        if ($this->hints > 0) {
            $this->hints -= 1;
            $this->save();
            return true;
        }
        return false;
    }

    public function resetHearts()
    {
        $this->hearts = 5;
        $this->save();
    }

    public function addScore(int $points)
    {
        $this->total_score += $points;
        $this->save();
    }
}