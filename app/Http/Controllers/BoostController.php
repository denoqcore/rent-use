<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function boost(Request $request, Listing $listing)
{
    $user = auth()->user();

    if ($listing->status !== 'active') {
    abort(404);
}

    if (!$user->canBoost()) {
        return back()->withErrors(['boost' => __('messages.boost-limit-reached')]);
    }

    $listing->update([
        'is_boosted'    => true,
        'boosted_until' => now()->addHours(24),
    ]);

    $user->increment('boosts_used_today');

    return back()->with('success', __('messages.boost-listing-booster'));
}
}
