<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ListingImage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'city_id',
        'title',
        'description',
        'slug',
        'price_per_day',
        'price_per_hour',
        'deposit',
        'currency',
        'delivery_available',
        'delivery_price',
        'requires_document',
        'status',
    ];

    protected $casts = [
        'delivery_available' => 'boolean',
        'requires_document'  => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('order');
    }

    public function mainImage()
    {
        return $this->images()->where('is_main', true)->first();
    }

    public function city()
    {
      return $this->belongsTo(Cities::class, 'city_id');
    }

    public function favoritedByUsers()
    {
    return $this->belongsToMany(User::class, 'favorites');
    }

    public function bookings()
    {
    return $this->hasMany(Booking::class);
    }


    public function activeBookings()
    {
    return $this->hasMany(Booking::class)
        ->whereIn('status', ['pending', 'confirmed']);
    }
}
