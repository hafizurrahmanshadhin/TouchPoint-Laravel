<?php

use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;

//! Route for Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//! Route for Subscription Plan Backend
Route::controller(SubscriptionPlanController::class)->group(function () {
    Route::get('/subscription-plan', 'index')->name('subscription-plan.index');
    Route::put('/subscription-plan/update/{id}', 'update')->name('subscription-plan.update');
    Route::get('/subscription-plan/status/{id}', 'status')->name('subscription-plan.status');
});
