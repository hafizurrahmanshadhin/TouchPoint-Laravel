<?php

use Twilio\TwiML\Video\Room;
use App\Models\AddTouchpoint;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\Contact\ContactController;
use App\Http\Controllers\Api\ChoosePlan\ChoosePlanController;
use App\Http\Controllers\Api\DeviceToken\DeviceTokenController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Subscription\SubscriptionController;
use App\Http\Controllers\Api\AddTouchpoint\AddTouchpointController;
use App\Http\Controllers\Api\FirebaseToken\FirebaseTokenController;

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
    ->names('api.contact');
// Route::get('/contact/list', [ContactController::class, 'index'])
//     ->name('api.contact.index');
Route::get('/contact/details/{id}', [ContactController::class, 'show'])
    ->name('api.contact.show');

Route::delete('/contact/delete/{id}', [ContactController::class, 'destroy'])
    ->name('api.contact.destroy');

// Add Touchpoint API route
// Route::resource('/add-touchpoint', AddTouchpointController::class)
//     ->names('api.add-touchpoint');

Route::get('/add-touchpoint/list', [AddTouchpointController::class, 'index'])
    ->name('api.add-touchpoint.index');

Route::get('/add-touchpoint/details/{id}', [AddTouchpointController::class, 'show'])
    ->name('api.add-touchpoint.show');

Route::post('/add-touchpoint/edit/{id}', [AddTouchpointController::class,'update']);

Route::delete('/add-touchpoint/delete/{id}',[AddTouchpointController::class,'destroy']);

// Subscription API route
Route::resource('/subscription', SubscriptionController::class)
    ->names('api.subscription');
    Route::get('/subscription/list', [SubscriptionController::class, 'show'])->name('api.subscription.show');




// Firebase Token Module
Route::get("firebase/test", [FirebaseTokenController::class, "test"]);
Route::post("firebase/token/add", [FirebaseTokenController::class, "store"]);
Route::post("firebase/token/get", [FirebaseTokenController::class, "getToken"]);
Route::post("firebase/token/delete", [FirebaseTokenController::class, "deleteToken"]);

// Emergency Route for sending emergency notification
Route::get('/notifications', [NotificationController::class, 'getNotifications'])->middleware('auth.jwt');

    
