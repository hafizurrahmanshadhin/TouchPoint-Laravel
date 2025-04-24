<?php

use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TouchPointController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.jwt')->group(function () {
    // Subscription routes
    Route::controller(SubscriptionController::class)->group(function () {
        // This route is used to retrieve the list of available subscriptions.
        Route::get('/subscription/list', 'index');
        // This route is used to choose a subscription plan or upgrade/downgrade an existing plan.
        Route::post('/subscription/choose', 'choose');
    });

    // TouchPoint routes
    Route::controller(TouchPointController::class)->group(function () {
        Route::post('/touch-points/create', 'createTouchPoint');
    });
});
