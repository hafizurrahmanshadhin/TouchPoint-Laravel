<?php

use App\Http\Controllers\Api\HomeController;
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
        // This route is for creating a new touch point.
        Route::post('/touch-points/create', 'createTouchPoint');
        // This route is for showing the summary of a specific touch point.
        Route::get('/touch-points/summary/{id}', 'summaryTouchPoint');
        // This route is for update a specific touch point.
        Route::post('/touch-points/update/{id}', 'updateTouchPoint');
    });

    // Home routes
    Route::controller(HomeController::class)->prefix('home')->group(function () {
        Route::get('/touch-points/list', 'listTouchPoints');
    });
});
