<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_number',
        'level_name',
        'is_premium',
        'coin_price',
        'reward_coins',
        'is_active',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    // Helper Methods
    public function isPremium(): bool
    {
        return $this->is_premium;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}