<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user     = Auth::user();
        $listings = $user->listings()->with('images')->latest()->get();
        $bookings = $user->bookingsAsRenter()->with(['listing.images', 'listing.city'])->latest()->get();

        $avgRating    = $user->averageRating();
        $reviewCount  = $user->reviewCount();
        $distribution = $user->ratingDistribution();

        $planLimits = [
        'starter' => 4,
        'pro'     => 12,
        'premium' => 20,
        ];
        $listingsCount = $listings->count();
        $listingsLimit = $planLimits[$user->plan] ?? 4;

        return view('profile.profile', compact(
            'user',
            'listings',
            'bookings',
            'avgRating',
            'reviewCount',
            'distribution',
            'listingsCount',
            'listingsLimit',
        ));
    }

    public function showPublic(User $user)
    {
        $listings = $user->listings()
            ->where('status', 'active')
            ->with(['images', 'category', 'city'])
            ->latest()
            ->paginate(10);

        $reviews      = $user->receivedReviews()->with(['reviewer', 'votes'])->latest()->get();
        $avgRating    = $user->averageRating();
        $reviewCount  = $user->reviewCount();
        $distribution = $user->ratingDistribution();
        $userReview   = auth()->check()
            ? $user->receivedReviews()->where('reviewer_id', auth()->id())->first()
            : null;

        $myVotes = auth()->check()
            ? \App\Models\ReviewVote::where('user_id', auth()->id())
                ->whereIn('review_id', $reviews->pluck('id'))
                ->pluck('is_like', 'review_id')
            : collect();

        return view('profile.show', [
            'profileUser'         => $user,
            'listings'            => $listings,
            'activeListingsCount' => $listings->total(),
            'reviews'             => $reviews,
            'avgRating'           => $avgRating,
            'reviewCount'         => $reviewCount,
            'distribution'        => $distribution,
            'userReview'          => $userReview,
            'myVotes'             => $myVotes,
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:64',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success_info', __('messages.profile-updated'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success_info', __('messages.avatar-updated'));
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
       'current_password' => __('messages.current-password-incorrect')]);
    }

    $user->update(['password' => Hash::make($request->password)]);

    return back()->with('success_password', __('messages.password-updated'));
}
}
