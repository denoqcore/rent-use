<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ChatController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::post('/favorites/{listing}', [FavoriteController::class, 'store'])->name('favorites.store');

Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    });

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::post('/refactor',       [ListingController::class, 'store'])->name('listings.store')->middleware('throttle:10,1');
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings',       [ListingController::class, 'store'])->name('listings.store')->middleware('throttle:10,1');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}',      [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}',   [ListingController::class, 'destroy'])->name('listings.destroy');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{listing}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::post('/bookings/{listing}', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/cancel',  [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/chat/{listing}', [ChatController::class, 'openOrCreate']);
    Route::post('/chat/{chat}/send', [ChatController::class, 'send']);
});

Route::get('/listings/{slug}', [ListingController::class, 'show'])->name('listings.show');

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ro', 'ru'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');
