<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'image_url',
        'cloudinary_public_id',
        'correct_answer',
        'points',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    // Helper Methods
    public function checkAnswer(string $userAnswer): bool
    {
        return strtoupper(trim($userAnswer)) === strtoupper(trim($this->correct_answer));
    }

    public function getHint(): string
    {
        $answer = strtoupper($this->correct_answer);
        $length = strlen($answer);
        $revealCount = max(1, intval($length / 3)); // reveal 1/3 dari huruf
        
        $positions = range(0, $length - 1);
        shuffle($positions);
        $revealPositions = array_slice($positions, 0, $revealCount);
        
        $hint = '';
        for ($i = 0; $i < $length; $i++) {
            if (in_array($i, $revealPositions)) {
                $hint .= $answer[$i];
            } else {
                $hint .= ' _';
            }
        }
        
        return $hint;
    }
    public function getImageUrlAttribute($value)
    {
        return asset($value);
    }
}