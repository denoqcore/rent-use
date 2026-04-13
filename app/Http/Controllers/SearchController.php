<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['images', 'category.parent'])
            ->where('status', 'active');

        // Search by type
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // filte by categ
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $childIds = $category->children->pluck('id');
                if ($childIds->isNotEmpty()) {
                    $query->whereIn('category_id', $childIds);
                } else {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // filte by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // filte by price
        if ($request->filled('price_min')) {
            $query->where(function ($b) use ($request) {
                $b->where('price_per_day', '>=', $request->price_min)
                  ->orWhere('price_per_hour', '>=', $request->price_min);
            });
        }
        if ($request->filled('price_max')) {
            $query->where(function ($b) use ($request) {
                $b->where('price_per_day', '<=', $request->price_max)
                  ->orWhere('price_per_hour', '<=', $request->price_max);
            });
        }

        // filte
        if ($request->boolean('delivery')) {
            $query->where('delivery_available', true);
        }

        // sort
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderByRaw('COALESCE(price_per_day, price_per_hour) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(price_per_day, price_per_hour) DESC'),
            default      => $query->latest(),
        };

        $listings   = $query->paginate(16)->withQueryString();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $cities     = Listing::where('status', 'active')->distinct()->pluck('city')->filter()->sort()->values();

        return view('search.search', compact('listings', 'categories', 'cities'));
    }
}
