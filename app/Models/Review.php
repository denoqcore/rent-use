<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    protected $fillable = ['reviewer_id', 'reviewed_user_id', 'rating', 'comment'];

    protected $casts = ['rating' => 'integer'];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function likesCount(): int
    {
        return $this->votes()->where('is_like', true)->count();
    }

    public function dislikesCount(): int
    {
        return $this->votes()->where('is_like', false)->count();
    }
}
