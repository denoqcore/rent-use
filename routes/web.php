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
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewVoteController;
use App\Http\Controllers\SubscriptionController;

Route::get('/listings/create',function(){return 'CREATE OK';});
Route::get('/',[HomeController::class,'index']);
Route::get('/search',[SearchController::class,'index'])->name('search');
Route::get('/listings',[ListingController::class,'index'])->name('listings.index');
Route::get('/listings/{slug}',[ListingController::class,'show'])->name('listings.show');
Route::get('/profile/{user:name}',[ProfileController::class,'showPublic'])->name('profile.public');

Route::get('/pricing', [SubscriptionController::class, 'index'])->name('subscription.index');
Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success')->middleware('auth');

Route::get('/lang/{locale}',function($locale){if(!in_array($locale,['en','ro','ru']))abort(400);session(['locale'=>$locale]);return redirect()->back();})->name('lang.switch');

Route::middleware('guest')->group(function(){
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::get('/register',[AuthController::class,'showRegister'])->name('register');
        Route::middleware('throttle:login')->group(function(){
          Route::post('/login',[AuthController::class,'login']);
          Route::post('/register',[AuthController::class,'register']);
        });
});


Route::middleware('auth')->prefix('subscription')->group(function () {
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});

// Without crf, auth
Route::post('/webhook/stripe', [SubscriptionController::class, 'webhook'])->name('stripe.webhook');

Route::middleware('auth')->group(function(){
    Route::get('/profile',[ProfileController::class,'show'])->name('profile');
    Route::post('/profile/info',[ProfileController::class,'updateInfo'])->name('profile.info');
    Route::post('/profile/avatar',[ProfileController::class,'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/password',[ProfileController::class,'updatePassword'])->name('profile.password');

    Route::prefix('listings')->group(function(){
    Route::get('/create',[ListingController::class,'create'])->name('listings.create');
    Route::post('/',[ListingController::class,'store'])->middleware('throttle:10,1')->name('listings.store');
    Route::get('/{listing:slug}/edit',[ListingController::class,'edit'])->name('listings.edit');
    Route::post('/{listing}/pause',[ListingController::class,'pause'])->name('listings.pause');
    Route::post('/{listing:slug}/archive',[ListingController::class,'archive'])->name('listings.archive');
    Route::post('/{listing}/restore',[ListingController::class,'restore'])->name('listings.restore');
    Route::delete('/{listing:slug}',[ListingController::class,'destroy'])->name('listings.destroy');
    Route::patch('/{listing}',[ListingController::class,'update'])->name('listings.update');
});

Route::get('/favorites',[FavoriteController::class,'index'])->name('favorites.index');
Route::post('/favorites/{listing}',[FavoriteController::class,'store'])->name('favorites.store');
Route::delete('/favorites/{listing}',[FavoriteController::class,'destroy'])->name('favorites.destroy');

Route::post('/users/{user}/reviews',[ReviewController::class,'store'])->name('reviews.store');
Route::delete('/reviews/{review}',[ReviewController::class,'destroy'])->name('reviews.destroy');
Route::post('/reviews/{review}/vote',[ReviewVoteController::class,'vote'])->name('reviews.vote');

Route::post('/bookings/{listing}',[BookingController::class,'store'])->middleware('throttle:20,1')->name('bookings.store');
Route::patch('/bookings/{booking}/confirm',[BookingController::class,'confirm'])->name('bookings.confirm');
Route::patch('/bookings/{booking}/cancel',[BookingController::class,'cancel'])->name('bookings.cancel');
Route::delete('/bookings/{booking}',[BookingController::class,'destroy']);

Route::get('/chats',[ChatController::class,'index']);
Route::get('/chats/{chat}',[ChatController::class,'show']);
Route::get('/chat/{listing}',[ChatController::class,'openOrCreate']);
Route::post('/chat/{chat}/send',[ChatController::class,'send'])->middleware('throttle:30,1');
Route::post('/user/{user}/message',[ChatController::class,'openOrCreateByUser'])->name('chat.user');

Route::get('/api/bookings/pending-count',[BookingController::class,'pendingCount']);
Route::get('/api/bookings',[BookingController::class,'apiIndex']);

Route::post('/logout',[AuthController::class,'logout'])->name('logout');
});
