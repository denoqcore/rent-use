<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ChatController;

RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('register', function (Request $request) {
    return Limit::perMinute(3)->by($request->ip());
});

RateLimiter::for('listing-create', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('favorites', function (Request $request) {
    return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('bookings', function (Request $request) {
    return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('chat', function (Request $request) {
    return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
});

Route::get('/', [HomeController::class, 'index']);

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

Route::get('/listings/{slug}', [ListingController::class, 'show'])
    ->name('listings.show');

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:register');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');

    Route::get('/listings/create', [ListingController::class, 'create'])
        ->name('listings.create');

    Route::post('/listings', [ListingController::class, 'store'])
        ->middleware('throttle:listing-create')
        ->name('listings.store');

    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])
        ->name('listings.edit');

    Route::put('/listings/{listing}', [ListingController::class, 'update'])
        ->middleware('throttle:listing-create')
        ->name('listings.update');

    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])
        ->middleware('throttle:listing-create')
        ->name('listings.destroy');

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');

    Route::post('/favorites/{listing}', [FavoriteController::class, 'store'])
        ->middleware('throttle:favorites')
        ->name('favorites.store');

    Route::delete('/favorites/{listing}', [FavoriteController::class, 'destroy'])
        ->middleware('throttle:favorites')
        ->name('favorites.destroy');

    Route::post('/bookings/{listing}', [BookingController::class, 'store'])
        ->middleware('throttle:bookings')
        ->name('bookings.store');

    Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])
        ->middleware('throttle:bookings')
        ->name('bookings.confirm');

    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->middleware('throttle:bookings')
        ->name('bookings.cancel');

    Route::get('/chats', [ChatController::class, 'index'])
        ->name('chats.index');

    Route::get('/chats/{chat}', [ChatController::class, 'show'])
        ->name('chats.show');

    Route::get('/chat/{listing}', [ChatController::class, 'openOrCreate'])
        ->name('chats.open');

    Route::post('/chat/{chat}/send', [ChatController::class, 'send'])
        ->middleware('throttle:chat')
        ->name('chats.send');
});

Route::get('/lang/{locale}', function ($locale) {

    if (!in_array($locale, ['en', 'ro', 'ru'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();

})->name('lang.switch');
