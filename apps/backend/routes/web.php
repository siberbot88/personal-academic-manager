<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;

// Google SSO Authentication
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/uploads/presign', [\App\Http\Controllers\UploadController::class, 'presign'])->name('uploads.presign');
    Route::post('/uploads/finalize', [\App\Http\Controllers\UploadController::class, 'finalize'])->name('uploads.finalize');
});