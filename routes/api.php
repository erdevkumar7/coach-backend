<?php

use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ServicePackages;
use App\Http\Controllers\Api\SimilarCoachesController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\SupportRequestController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;




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
Route::post('/ageGroups', [GuestController::class, 'getAllAgeGroup']);

Route::post('/getCoachType', [GuestController::class, 'getAllCoachType']);
Route::post('/getSubCoachType/{coach_type_id}', [GuestController::class, 'getAllSubCoachType']);
// Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
// Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
// Route::post('/getcoachprofile', [AuthController::class, 'getcoachprofile']);


    // VG Routes start

    // User service package api
    Route::get('/getalluserservicepackage', [ServicePackages::class, 'getAllUserServicePackage']);
    Route::post('/getuserservicepackage/{id}', [ServicePackages::class, 'getUserServicePackage']);
    Route::post('/adduserservicepackage', [ServicePackages::class, 'addUserServicePackage']);

    // Master price get api
    Route::get('/getmastersessionformats', [MasterController::class, 'GetMasterSessionFormats']);
    Route::get('/getmastercancellationpolicies', [MasterController::class, 'GetMasterCancellationPolicies']);

    Route::get('/getmasterprices', [MasterController::class, 'GetMasterPrices']);
    Route::get('/getmasterblogs', [MasterController::class, 'GetMasterBlogs']);
    Route::post('/similarcoaches', [SimilarCoachesController::class, 'SimilarCoaches']);
    Route::get('/subscriptionplans', [SubscriptionPlanController::class, 'SubscriptionPlans']);
    Route::get('/getfaqs', [FaqController::class, 'Getfaqs']);
    Route::post('/addsupportrequest', [SupportRequestController::class, 'AddSupportRequest']);

    // User reviews
    Route::get('/reviews', [ReviewController::class, 'Reviews']);
    Route::post('/userReviews', [ReviewController::class, 'UserReviews']);
    Route::post('/userReviewView', [ReviewController::class, 'UserReviewView']);
    Route::put('/userReviewUpdate', [ReviewController::class, 'UserReviewUpdate']);
    Route::post('/userReviewReply', [ReviewController::class, 'UserReviewReply']);

    // Coach Reviews
    Route::post('/coachReviews', [ReviewController::class, 'CoachReviews']);

    Route::post('/getServicePackageByCoach', [ServicePackages::class, 'GetServicePackageByCoach']);
    // VG Routes end

    Route::middleware('auth:api')->group(function () {
        Route::post('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        // Route::post('/coachlist', [AuthController::class, 'index']);
        // Route::post('/coachDetails', [AuthController::class, 'coachDetails']);
        Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
        Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
        // Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
        Route::post('/updateProfileImage', [UserController::class, 'updateProfileImage']);

        // VG Code login

        //VG Code login
    });

