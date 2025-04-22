<?php

use App\Http\Controllers\Web\Backend\CategoriesController;
use App\Http\Controllers\Web\Backend\ChoosePlan\ChoosePlanController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\ServicesController;
use App\Models\ChoosePlan;
use Illuminate\Support\Facades\Route;

//! Route for Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


// Route for Admin Choose Plan
Route::resource('/choose-plan', ChoosePlanController::class)->names('choose.plan')->middleware('auth');
Route::post('/choose-plan/status/{id}', [ChoosePlanController::class, 'status'])->name('choose.status');

// Route::resource('/user-list', UserController::class)->names('user-list');
// Route::post('/user-list/status/{id}', [UserController::class, 'status'])->name('user-list.status');

// Use group route