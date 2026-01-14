<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;

// Google SSO Authentication
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');


Route::get('/', function () {
    return view('welcome');
});