<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProfileController;
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
        // This route is for deleting a specific touch point.
        Route::delete('/touch-points/delete/{id}', 'deleteTouchPoint');
    });

    // Home routes
    Route::controller(HomeController::class)->prefix('home')->group(function () {
        // This route is for showing the list of all touch points.
        Route::get('/touch-points/list', 'listTouchPoints');
        // Thi route is for showing a specific touch point details.
        Route::get('/touch-points/show/details/{id}', 'showSpecificTouchPointDetails');
        // This route is for Reset a specific touch point.
        Route::post('/touch-points/reset/{id}', 'resetTouchPoint');
    });

    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        // This route is for retrieving the authenticated user’s profile.
        Route::get('/profile', 'getProfile');
        // This route is for the list of all completed touch points.
        Route::get('/touch-points/completed', 'completedTouchPointsList');
        // This route is for upcoming touch points list
        Route::get('/touch-points/upcoming', 'upcomingTouchPointsList');
        // This route is for showing the activity of touch points.
        Route::get('/touch-points/activity', 'touchPointActivity');
    });
});
