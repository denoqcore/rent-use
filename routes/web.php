<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\SearchController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    });

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings',       [ListingController::class, 'store'])->name('listings.store')->middleware('throttle:10,1');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}',      [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}',   [ListingController::class, 'destroy'])->name('listings.destroy');
});

Route::get('/listings/{slug}', [ListingController::class, 'show'])->name('listings.show');

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ro', 'ru'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');
