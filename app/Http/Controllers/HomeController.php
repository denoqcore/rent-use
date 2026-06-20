<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
{
    $categories = Category::whereNull('parent_id')->with('children')->get();

    $listings = Listing::with(['images', 'category.parent', 'city', 'user'])
        ->where('status', 'active')
        ->orderByRaw("
            CASE
                WHEN EXISTS (
                    SELECT 1 FROM users
                    WHERE users.id = listings.user_id
                    AND users.plan = 'premium'
                    AND users.plan_expires_at > NOW()
                ) THEN 0
                WHEN EXISTS (
                    SELECT 1 FROM users
                    WHERE users.id = listings.user_id
                    AND users.plan = 'pro'
                    AND users.plan_expires_at > NOW()
                ) THEN 1
                ELSE 2
            END
        ")
        ->orderByRaw("CASE WHEN is_boosted = 1 AND boosted_until > NOW() THEN 0 ELSE 1 END")
        ->latest()
        ->take(8)
        ->get();

    if (auth()->check()) {
        auth()->user()->load('favoriteListings');
    }

    return view('home', compact('categories', 'listings'));
}
}
