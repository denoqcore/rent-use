<?php

namespace App\Providers;
use App\Models\Chat;
use App\Policies\ChatPolicy;
use App\Models\Listing;
use App\Policies\ListingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Listing::class, ListingPolicy::class);
        Gate::policy(Chat::class, ChatPolicy::class);

        RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)->by($request->email . '|' . $request->ip());
    });
    }
}
