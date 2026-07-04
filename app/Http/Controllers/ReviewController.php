<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', __('messages.cannot-review-yourself'));
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'reviewer_id'      => Auth::id(),
                'reviewed_user_id' => $user->id,
            ],
            [
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return back()->with('success', __('messages.review-submitted'));
    }

    public function destroy(Review $review)
    {
        abort_unless(Auth::id() === $review->reviewer_id, 403);
        $review->delete();
        return back()->with('success', __('messages.review-deleted'));
    }
}
