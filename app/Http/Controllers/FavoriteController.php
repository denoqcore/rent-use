<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favoriteListings()
            ->with(['images' => fn($q) => $q->where('is_main', true)])
            ->where('status', 'active')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function store(Listing $listing)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', __('messages.auth_required_favorite'));
        }

        auth()->user()->favorites()->firstOrCreate([
            'listing_id' => $listing->id,
        ]);

        return back();
    }

    public function destroy(Listing $listing)
    {
        auth()->user()->favorites()
            ->where('listing_id', $listing->id)
            ->delete();

        return back();
    }
}
