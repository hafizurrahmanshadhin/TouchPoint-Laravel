<?php

use App\Http\Controllers\Api\Subscription\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.jwt')->group(function () {
    Route::controller(SubscriptionController::class)->group(function () {
        Route::get('/subscription/list', 'index');
        Route::post('/subscription/choose', 'choose');
    });
});
