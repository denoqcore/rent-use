<?php

namespace App\Models;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingImage extends Model
{
    protected $fillable = ['listing_id', 'path', 'is_main', 'order'];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
