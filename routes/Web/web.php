<?php

use App\Http\Controllers\ResetController;
use App\Http\Controllers\Web\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

//! Route for Reset Database and Optimize Clear
Route::get('/reset', [ResetController::class, 'RunMigrations'])->name('reset');

//! Route for Landing Page
Route::get('/', [HomeController::class, 'index'])->name('index');


// Route::get('/auth/redirect/google', [SocialiteController::class, 'redirectToGoogle']);
// Route::get('/auth/callback/google', [SocialiteController::class, 'handleGoogleCallback']);