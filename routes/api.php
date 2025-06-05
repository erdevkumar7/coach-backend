<?php

use App\Http\Controllers\Api\ReusableComponentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/status', function () {
    return response()->json(['status' => 'API is working']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/userlogin', [AuthController::class, 'userLogin']);
Route::post('/coachlogin', [AuthController::class, 'coachLogin']);

Route::post('/getCountries', [AuthController::class, 'getCountries']);

Route::post('/coachlist', [AuthController::class, 'index']);
Route::post('/coachDetails', [AuthController::class, 'coachDetails']);
Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
Route::post('/getcoachprofile', [AuthController::class, 'getcoachprofile']);

Route::middleware('auth:api')->group(function () {
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::post('/updateProfile', [AuthController::class, 'updateProfile']);


});

//pageBuilder ------------- ErDev -----------------------------------------
Route::post('/builder-register', [AuthController::class, 'builderRegister']);
Route::post('/builder-login', [AuthController::class, 'builderlogin']); 
Route::post('/grapesjs_project/{page_name}', [AuthController::class, 'grapesjs_project']);
Route::get('/grapesjs_project/load/{id}/{userid}', [AuthController::class, 'loadGrapesjsProject']);
Route::get('/grapesjs_html/{id}/{userid}', [AuthController::class, 'grapesjsHtml']);


Route::post('/components', [ReusableComponentController::class, 'store']);
Route::get('/components', [ReusableComponentController::class, 'index']);
Route::get('/components/{id}', [ReusableComponentController::class, 'show']);
Route::put('/components/{id}', [ReusableComponentController::class, 'update']);
Route::delete('/components/{id}', [ReusableComponentController::class, 'destroy']);

Route::post('/form-submission', [ReusableComponentController::class, 'formStore']);
Route::get('/form-submissions/form/{id}', [ReusableComponentController::class, 'FormShow']);
//pageBuilder