<?php

use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

//# Auth Routes Start
Route::prefix('auth')->middleware(['throttle:10,1'])->group(function () {
    Route::post('/socialite-login', [SocialiteController::class, 'socialiteLogin']);
    Route::post('/logout', [LogoutController::class, 'logout'])->middleware(['auth.jwt']);
});
//~ Auth Routes End
