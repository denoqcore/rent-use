<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    use AuthorizesRequests;
    public function create()

{
    $cities = Cities::orderBy('order')->get();
    $categories = Category::whereNull('parent_id')->with('children')->get();
    $selectedCity = old('city_id')
    ? Cities::find(old('city_id'))
    : null;
    return view('listings.create', compact('categories', 'cities', 'selectedCity'));
}
    public function index()
    {
        $listings = Listing::query()
        ->with(['images', 'category.parent', 'city', 'user'])
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
        ->orderByRaw("
            CASE WHEN is_boosted = 1 AND boosted_until > NOW() THEN 0 ELSE 1 END
        ")
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('listings.index', compact('listings'));
}

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->listings()->where('status', 'active')->count() >= $user->maxListings()) {
            return back()->withErrors([
                'limit' => __('messages.max-active-listings'),
            ]);
    }


        $validated = $request->validate([
        'category_id'        => 'required|exists:categories,id',
        'title'              => 'required|string|min:5|max:100',
        'description'        => 'required|string|min:10|max:2000',
        'city_id'            => 'required|exists:cities,id',
        'price_per_day'      => 'nullable|numeric|min:1|max:99999',
        'price_per_hour'     => 'nullable|numeric|min:1|max:99999',
        'deposit'            => 'nullable|numeric|min:0|max:999999',
        'currency'           => 'required|in:MDL,EUR,USD',
        'delivery_price'     => 'nullable|numeric|min:0|max:99999',
        'delivery_available' => 'nullable|boolean',
        'requires_document'  => 'nullable|boolean',
        'images'             => 'nullable|array|max:8',
        'images.*'           => 'image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

        $maxPhotos = $user->maxPhotos();
        $photos = array_slice($request->file('images', []), 0, $maxPhotos);

        if (empty($validated['price_per_day']) && empty($validated['price_per_hour'])) {

       return back()->withErrors(['price_per_day' =>  __('messages.at-least-one-price-required')])->withInput();
    }

        $listing = Listing::create([
            'user_id'            => Auth::id(),
            'category_id'        => $validated['category_id'],
            'title'              => $validated['title'],
            'description'        => $validated['description'],
            'slug'               => Str::slug($validated['title']) . '-' . uniqid(),
            'city_id'            => $validated['city_id'],
            'price_per_day' => $validated['price_per_day'] ?? null,
            'price_per_hour'     => $validated['price_per_hour'] ?? null,
            'deposit'            => $validated['deposit'] ?? null,
            'currency'           => $validated['currency'],
            'delivery_available' => $request->boolean('delivery_available'),
            'delivery_price'     => $validated['delivery_price'] ?? null,
            'requires_document'  => $request->boolean('requires_document'),
            'status'             => 'active',
        ]);

        if ($request->hasFile('images')) {
            foreach ($photos as $index => $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create([
                    'path'    => $path,
                    'is_main' => $index === 0,
                    'order'   => $index,
                ]);
            }
    }

        return redirect()->route('listings.show', $listing->slug)
            ->with('success',  __('messages.listing-published'));
    }

    public function show(string $slug)
{
    $listing = Listing::with([
        'images',
        'category.parent',
        'city',
        'user',
    ])->where('slug', $slug)->firstOrFail();

    if ($listing->status !== 'active' && $listing->user_id !== Auth::id()) {
        abort(404);
    }

    $bookedDates = $listing->activeBookings()
        ->get(['start_date', 'end_date'])
        ->flatMap(function ($booking) {
            $dates = [];
            $current = $booking->start_date->copy();
            while ($current->lte($booking->end_date)) {
                $dates[] = $current->format('Y-m-d');
                $current->addDay();
            }
            return $dates;
        })
        ->unique()
        ->values()
        ->toArray();

    $initialMode = $listing->price_per_day ? 'day' : 'hour';

return view('listings.show', compact('listing', 'bookedDates', 'initialMode'));
}

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $cities = Cities::orderBy('order')->get();
        return view('listings.edit', compact('listing', 'categories', 'cities'));
    }

    public function update(Request $request, Listing $listing)
    {
    $this->authorize('update', $listing);

    $validated = $request->validate([
        'category_id'        => 'required|exists:categories,id',
        'title'              => 'required|string|min:5|max:100',
        'description'        => 'required|string|min:10|max:2000',
        'city_id'            => 'required|exists:cities,id',
        'price_per_day'      => 'nullable|numeric|min:1|max:99999',
        'price_per_hour'     => 'nullable|numeric|min:1|max:99999',
        'deposit'            => 'nullable|numeric|min:0|max:999999',
        'currency'           => ['required', 'in:MDL,EUR,USD'],
        'delivery_available' => 'nullable|boolean',
        'delivery_price'     => 'nullable|numeric|min:0|max:99999',
        'requires_document'  => 'nullable|boolean',
        'images'             => 'nullable|array|max:8',
        'images.*'           => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        'delete_images'      => 'nullable|array',
        'delete_images.*'    => 'exists:listing_images,id',
    ]);

    if (empty($validated['price_per_day']) && empty($validated['price_per_hour'])) {
        return back()->withErrors(['price_per_day' => __('messages.at-least-one-price-required')])->withInput();
    }

    $listing->update([
        'category_id'        => $validated['category_id'],
        'title'              => $validated['title'],
        'description'        => $validated['description'],
        'city_id'            => $validated['city_id'],
        'price_per_day'      => $validated['price_per_day'] ?? null,
        'price_per_hour'     => $validated['price_per_hour'] ?? null,
        'deposit'            => $validated['deposit'] ?? null,
        'currency'           => $validated['currency'],
        'delivery_available' => $request->boolean('delivery_available'),
        'delivery_price'     => $validated['delivery_price'] ?? null,
        'requires_document'  => $request->boolean('requires_document'),
    ]);

    if (!empty($validated['delete_images'])) {
        $listing->images()->whereIn('id', $validated['delete_images'])->each(function ($img) {
            \Storage::disk('public')->delete($img->path);
            $img->delete();
        });
    }

    if ($request->hasFile('images')) {
    $maxPhotos = $listing->user->maxPhotos();
    $currentCount = $listing->images()->count();
    $allowed = max(0, $maxPhotos - $currentCount);
    foreach (array_slice($request->file('images'), 0, $allowed) as $index => $image) {
        $path = $image->store('listings', 'public');
        $listing->images()->create([
            'path'    => $path,
            'is_main' => $currentCount === 0 && $index === 0,
            'order'   => $currentCount + $index,
        ]);
    }
}

    return redirect()->route('listings.show', $listing->slug)
        ->with('success', __('messages.listing-updated'));
}


public function pause(Listing $listing)
{
    $this->authorize('update', $listing);

    if ($listing->status === 'paused') {
        $user = $listing->user;
        if ($user->listings()->where('status', 'active')->count() >= $user->maxListings()) {
            return back()->withErrors([
                'limit' => __('messages.max-active-listings')
            ])->withFragment('listings');
        }
        $listing->update(['status' => 'active', 'paused_reason' => null]);
        return redirect()->route('profile')->withFragment('listings')->with('success', __('messages.listing-active'));
    }

    $listing->update(['status' => 'paused', 'paused_reason' => 'manual']);
    return redirect()->route('profile')->withFragment('listings')->with('success', __('messages.listing-paused'));
}

public function restore(Listing $listing)
{
    $this->authorize('update', $listing);

    $user = $listing->user;
    if ($user->listings()->where('status', 'active')->count() >= $user->maxListings()) {
        return back()->withErrors([
            'limit' => __('messages.plan_limit_reached')
        ])->withFragment('listings');
    }

    $listing->update(['status' => 'active', 'paused_reason' => null]);
    return redirect()->route('profile')->withFragment('listings')->with('success', __('messages.listing-restored'));
}

public function archive(Listing $listing)
{
    $this->authorize('update', $listing);

    $listing->update([
        'status' => 'archived'
    ]);

    return redirect()
        ->route('profile')
        ->withFragment('listings')
        ->with('success',  __('messages.listing-archived'));
}

public function destroy(Listing $listing)
{
    $this->authorize('delete', $listing);

    foreach ($listing->images as $image) {
        \Storage::disk('public')->delete($image->path);
    }

    $listing->delete();

    return redirect()->route('profile')
        ->withFragment('listings')
        ->with('success', __('messages.listing-deleted'));
}
}
