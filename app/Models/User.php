<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'is_admin',
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'is_online',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_seen_at'      => 'datetime',
            'is_online'         => 'boolean',
            'is_admin'          => 'boolean',
            'password'          => 'hashed',
        ];
    }

     public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function listings()
{
    return $this->hasMany(Listing::class);
}

    public function favoriteListings()
    {
        return $this->belongsToMany(Listing::class, 'favorites');
    }

    public function bookingsAsRenter()
    {
        return $this->hasMany(Booking::class, 'renter_id');
    }

    public function bookingsAsOwner()
    {
        return $this->hasMany(Booking::class, 'owner_id');
    }

    public function chatsAsOwner()
    {
        return $this->hasMany(Chat::class, 'owner_id');
    }

    public function chatsAsRenter()
    {
        return $this->hasMany(Chat::class, 'renter_id');
    }

    public function chats()
    {
        return Chat::where('owner_id', $this->id)
                   ->orWhere('renter_id', $this->id);
    }
}
