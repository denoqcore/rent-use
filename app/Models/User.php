<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Models\Review;
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
        // 'plan',
        // 'plan_expires_at',
        // 'boosts_used_today',
        // 'boosts_reset_date',
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
            'plan_expires_at'   => 'datetime',
            'boosts_reset_date' => 'date',
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
    return $this->belongsToMany(Listing::class, 'favorites')
        ->withTrashed();
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

       public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function averageRating(): float
    {
        return round($this->receivedReviews()->avg('rating') ?? 0, 1);
    }

    public function reviewCount(): int
    {
        return $this->receivedReviews()->count();
    }

    public function ratingDistribution(): array
    {
        $counts = $this->receivedReviews()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $result = [];
        for ($star = 5; $star >= 1; $star--) {
            $result[$star] = $counts[$star] ?? 0;
        }

        return $result;
    }

    // plans

    public function isActivePlan(): bool
{
    if ($this->plan === 'starter') return true;

    $active = $this->plan_expires_at && $this->plan_expires_at->isFuture();

    if (!$active && $this->plan !== 'starter') {
        $this->forceFill(['plan' => 'starter', 'plan_expires_at' => null])->save();
    }

    return $active;
}


    public function planLabel(): string
    {
        if (!$this->isActivePlan()) {
        return 'STARTER';
    }

        return match($this->plan) {
            'pro'     => 'PRO',
            'premium' => 'PREMIUM',
            default   => 'STARTER',
        };
    }

    public function maxListings(): int
    {
        if (!$this->isActivePlan()) return config('plans.starter.listings', 4);
        return config("plans.{$this->plan}.listings", config('plans.starter.listings', 4));
    }

    public function maxPhotos(): int
    {
        if (!$this->isActivePlan()) return config('plans.starter.photos', 3);
        return config("plans.{$this->plan}.photos", config('plans.starter.photos', 3));
    }

    public function maxBoostedListings(): int
    {
        if (!$this->isActivePlan()) return 0;
        return match($this->plan) {
            'pro'     => 2,
            'premium' => 4,
            default   => 1,
        };
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

     public function boostLimitPerDay(): int
    {
        $this->isActivePlan();

        return config("plans.{$this->plan}.boosts", config('plans.starter.boosts', 1));
    }

 public function canBoost(): bool
{
    if ($this->boosts_reset_date !== now()->toDateString()) {
        $this->forceFill([
            'boosts_used_today' => 0,
            'boosts_reset_date' => now()->toDateString(),
        ])->save();
    }

    return $this->fresh()->boosts_used_today < $this->boostLimitPerDay();
}
}
