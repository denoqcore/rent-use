<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class BoostController extends Controller
{
    public function boost(Request $request, Listing $listing)
    {
        $user = auth()->user();

        if ($listing->user_id !== $user->id) {
            abort(403);
        }

        $listing->is_boosted = true;
        $listing->boosted_until = now()->addHours(24);
        $listing->save();

        $user->boosts_used_today = ($user->boosts_used_today ?? 0) + 1;
        $user->save();

        return back()->with('success', __('messages.boost-listing-booster'));
    }
}
