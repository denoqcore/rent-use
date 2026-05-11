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

        $listings = Listing::with(['images', 'category.parent', 'city'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        if (auth()->check()) {
            auth()->user()->load('favoriteListings');
        }

        return view('home', compact('categories', 'listings'));
    }
}
