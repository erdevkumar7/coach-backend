<?php

use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/status', function () {
    return response()->json(['status' => 'API is working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/coachlist', [AuthController::class, 'index']);
Route::post('/coachDetails', [AuthController::class, 'coachDetails']);

Route::post('/getCountries', [GuestController::class, 'getAllCountries']);
Route::post('/getStates/{country_id}', [GuestController::class, 'getStateOfaCountry']);
Route::post('/getCities/{state_id}', [GuestController::class, 'getCitiesOfaState']);

Route::post('/getDeliveryMode', [GuestController::class, 'deliveryAllMode']);
Route::post('/getLanguages', [GuestController::class, 'getAllLanguages']);

// Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
// Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
// Route::post('/getcoachprofile', [AuthController::class, 'getcoachprofile']);

Route::middleware('auth:api')->group(function () {
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']); 
    // Route::post('/coachlist', [AuthController::class, 'index']);
    // Route::post('/coachDetails', [AuthController::class, 'coachDetails']);
    Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
     // Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
     Route::post('/updateProfileImage', [UserController::class, 'updateProfileImage']);
    
});

