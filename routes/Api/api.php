<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ChoosePlan\ChoosePlanController;

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World']);
});


Route::get('/choose-plan/list', [ChoosePlanController::class, 'index'])
    ->name('api.choose-plan.index');

Route::get('/choose-plan/details/{id}', [ChoosePlanController::class, 'show'])
    ->name('api.choose-plan.show');

// Route::get('/chose-plan/details/{id}', [ChoosePlanController::class, 'details'])
//     ->name('api.choose-plan.details');
// Route::get('/choose-plan/{id}/services', [ChoosePlanController::class, 'services'])
//     ->name('api.choose-plan.services');
// Route::get('/choose-plan/{id}/categories', [ChoosePlanController::class, 'categories'])
//     ->name('api.choose-plan.categories');

