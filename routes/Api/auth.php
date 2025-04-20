<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

//# Auth Routes Start
Route::prefix('auth')->middleware(['throttle:10,1'])->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LogoutController::class, 'logout'])->middleware(['auth.jwt']);

    Route::controller(PasswordResetController::class)
        ->group(function () {
            Route::post('/send-otp', 'sendOtpToEmail');
            Route::post('/verify-otp', 'verifyOTP');
            Route::post('/reset-password', 'resetPassword');
        });

    Route::post('/socialite-login', [SocialiteController::class, 'socialiteLogin']);
 
    // Route::get('/login/callback', [SocialiteController::class, 'socialiteCallback']);
    //   Route::post('/socialLogin', [SocialiteController::class, 'SocialLogin']);
    //     Route::post('/logout', [SocialiteController::class, 'logout'])->middleware(['auth.jwt']);
    //     Route::get('/profile', [SocialiteController::class, 'getProfile'])->middleware(['auth.jwt']);

 Route::post('/socialLogin', [SocialiteController::class, 'SocialLogin']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [SocialiteController::class, 'logout']);
    Route::get('/profile', [SocialiteController::class, 'getProfile']);


});
    


});

