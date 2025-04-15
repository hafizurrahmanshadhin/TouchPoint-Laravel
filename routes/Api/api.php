<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\Contact\ContactController;
use App\Http\Controllers\Api\ChoosePlan\ChoosePlanController;

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World']);
});


// choose-plan api route

Route::get('/choose-plan/list', [ChoosePlanController::class, 'index'])
    ->name('api.choose-plan.index');

Route::get('/choose-plan/details/{id}', [ChoosePlanController::class, 'show'])
    ->name('api.choose-plan.show');

// Contact API route
Route::resource('/contact', ContactController::class)
    ->names('api.contact')
    ->only(['index', 'store', 'show', 'update', 'destroy']);

    

// Route::get('/contact/list', [ContactController::class, 'index'])
//     ->name('api.contact.index');
// Route::get('/contact/details/{id}', [ContactController::class, 'show'])
//     ->name('api.contact.show');

