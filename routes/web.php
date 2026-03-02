<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});



// LANGUAGE
Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ro'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return redirect()->back();
})->name('lang.switch');
