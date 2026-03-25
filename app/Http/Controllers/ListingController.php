<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function create()
{
    $categories = Category::whereNull('parent_id')->with('children')->get();
    return view('listings.create', compact('categories'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|min:5|max:100',
            'description'      => 'required|string|min:10|max:2000',
            'city'             => 'required|string|max:60',
            'price_per_day'    => 'required|numeric|min:1|max:999999',
            'price_per_hour'   => 'nullable|numeric|min:1|max:999999',
            'deposit'          => 'nullable|numeric|min:0|max:999999',
            'currency'         => ['required', 'in:MDL,EUR,USD'],
            'delivery_price'   => 'nullable|numeric|min:0|max:99999',
            'delivery_available' => 'nullable|boolean',
            'requires_document'  => 'nullable|boolean',
            'images'           => 'nullable|array|max:8',
            'images.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $listing = Listing::create([
            'user_id'            => Auth::id(),
            'category_id'        => $validated['category_id'],
            'title'              => $validated['title'],
            'description'        => $validated['description'],
            'slug'               => Str::slug($validated['title']) . '-' . uniqid(),
            'city'               => $validated['city'],
            'price_per_day'      => $validated['price_per_day'],
            'price_per_hour'     => $validated['price_per_hour'] ?? null,
            'deposit'            => $validated['deposit'] ?? null,
            'currency'           => $validated['currency'],
            'delivery_available' => $request->boolean('delivery_available'),
            'delivery_price'     => $validated['delivery_price'] ?? null,
            'requires_document'  => $request->boolean('requires_document'),
            'status'             => 'active',
        ]);

        if ($request->hasFile('images')) {
            foreach (array_slice($request->file('images'), 0, 8) as $index => $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create([
                    'path'    => $path,
                    'is_main' => $index === 0,
                    'order'   => $index,
                ]);
            }
        }

        return redirect()->route('listings.show', $listing->slug)
            ->with('success', 'Listing published!');
    }

    public function show(string $slug)
    {
        $listing = Listing::where('slug', $slug)
            ->with(['user', 'category', 'images'])
            ->firstOrFail();

        return view('listings.show', compact('listing'));
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('listings.edit', compact('listing', 'categories'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string',
            'category_id'        => 'required|exists:categories,id',
            'city'               => 'required|string|max:100',
            'price_per_day'      => 'required|integer|min:1',
            'price_per_hour'     => 'nullable|integer|min:1',
            'deposit'            => 'nullable|integer|min:0',
            'currency'           => ['required', 'in:MDL,EUR,USD'],
            'delivery_available' => 'nullable|boolean',
            'delivery_price'     => 'nullable|integer|min:0',
            'requires_document'  => 'nullable|boolean',
        ]);

        $listing->update($validated);

        return redirect()->route('listings.show', $listing->slug)
            ->with('success', 'Listing updated!');
    }

    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);
        $listing->update(['status' => 'archived']);
        return redirect()->route('profile')->with('success', 'Listing archived.');
    }
}
