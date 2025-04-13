<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ServicesController;

Route::get('/test', function () {
    return response()->json(['message' => 'Hello World']);
});

