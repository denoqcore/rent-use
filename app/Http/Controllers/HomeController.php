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

    $listings = Listing::with(['images', 'category'])
        ->where('status', 'active')
        ->latest()
        ->take(8)
        ->get();

    return view('home', compact('categories', 'listings'));
}
}
