<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewVote;
use Illuminate\Http\Request;

class ReviewVoteController extends Controller
{
    public function vote(Request $request, Review $review)
    {
        $request->validate(['is_like' => 'required|boolean']);

        if (auth()->id() === $review->reviewer_id) {
            return response()->json(['error' => 'Cannot vote on your own review'], 403);
        }

        $existing = ReviewVote::where('user_id', auth()->id())
            ->where('review_id', $review->id)
            ->first();

        if ($existing) {
            if ($existing->is_like === $request->boolean('is_like')) {
                $existing->delete();
            } else {
                $existing->update(['is_like' => $request->boolean('is_like')]);
            }
        } else {
            ReviewVote::create([
                'user_id'   => auth()->id(),
                'review_id' => $review->id,
                'is_like'   => $request->boolean('is_like'),
            ]);
        }

        return response()->json([
            'likes'    => $review->fresh()->likesCount(),
            'dislikes' => $review->fresh()->dislikesCount(),
        ]);
    }
}
