<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cities extends Model
{
    protected $fillable = [
        'name_ru',
        'name_ro',
        'name_en',
        'slug',
        'is_suburb',
        'order',
    ];

    protected $casts = [
        'is_suburb' => 'boolean',
    ];


    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }


    public function getNameAttribute(): string
    {
        return match (app()->getLocale()) {
            'ru' => $this->name_ru,
            'ro' => $this->name_ro,
            'en' => $this->name_en,
            default => $this->name_en,
        };
    }
}
