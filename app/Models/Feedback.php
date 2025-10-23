<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'message',
        'status',
        'admin_reply',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function markAsReviewed(string $reply = null)
    {
        $this->status = 'reviewed';
        $this->admin_reply = $reply;
        $this->reviewed_at = now();
        $this->save();
    }

    public function markAsResolved()
    {
        $this->status = 'resolved';
        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}