<?php

use App\Http\Controllers\Api\CalendarBookingController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\FavoriteCoachController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SendCoachMessageController;
use App\Http\Controllers\Api\ServicePackages;
use App\Http\Controllers\Api\SimilarCoachesController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\SupportRequestController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CochingRequestController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;









Route::get('/status', function () {
    return response()->json(['status' => 'API is working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/coachlist', [AuthController::class, 'coachlist']);
Route::post('/coachDetails', [AuthController::class, 'coachDetails']);

Route::get('/getallmastercategories', [GuestController::class, 'getallmastercategories']);

Route::post('/getCountries', [GuestController::class, 'getAllCountries']);
Route::post('/getStates/{country_id}', [GuestController::class, 'getStateOfaCountry']);
Route::post('/getCities/{state_id}', [GuestController::class, 'getCitiesOfaState']);

Route::post('/getDeliveryMode', [GuestController::class, 'deliveryAllMode']);
Route::post('/getLanguages', [GuestController::class, 'getAllLanguages']);
Route::post('/ageGroups', [GuestController::class, 'getAllAgeGroup']);  
Route::post('/coachingCategories', [GuestController::class, 'getAllCoachingCategories']);
Route::post('/sessionFormats', [GuestController::class, 'getAllSessionFormats']);
Route::post('/priceModels', [GuestController::class, 'getAllPriceModels']);

Route::post('/getCoachType', [GuestController::class, 'getAllCoachType']);
Route::post('/getSubCoachType/{coach_type_id}', [GuestController::class, 'getAllSubCoachType']);
Route::post('/getAllCoachServices', [GuestController::class, 'getAllCoachServices']);
// Route::post('/getcoachprofile', [AuthController::class, 'getcoachprofile']);


// VG Routes start
Route::post('/getMasterBudgetRange', [MasterController::class, 'getMasterBudgetRange']);
Route::post('/coachExperienceLevel', [MasterController::class, 'coachExperienceLevel']);
Route::post('/communicationChannel', [MasterController::class, 'communicationChannel']);


Route::get('/getmasterprices', [MasterController::class, 'GetMasterPrices']);
Route::post('/getmasterblogs', [MasterController::class, 'GetMasterBlogs']);
Route::post('/similarcoaches', [SimilarCoachesController::class, 'SimilarCoaches']);
Route::get('/subscriptionplans', [SubscriptionPlanController::class, 'SubscriptionPlans']);
Route::get('/getfaqs', [FaqController::class, 'Getfaqs']);
Route::post('/addsupportrequest', [SupportRequestController::class, 'AddSupportRequest']);

Route::post('/coachCalendarBookingDetails', [CalendarBookingController::class, 'coachCalendarBookingDetails']);
Route::post('/date_time_avalibility', [ServicePackages::class, 'date_time_avalibility']);


// VG Routes end
Route::get('email/changeStatus', [AuthController::class ,'change_user_status']);
Route::post('/getuserservicepackage/{id}', [ServicePackages::class, 'getUserServicePackage']);
Route::post('/getServicePackageById/{coach_id}/{package_id}', [ServicePackages::class, 'getServicePackageById']);
Route::post('/get_AarrayOfServicePackageIds_ByCoachId/{coach_id}', [ServicePackages::class, 'getAarrayOfServicePackageIdsByCoachId']);

Route::middleware('auth:api')->group(function () {
    Route::post('/validateToken', [AuthController::class, 'validateToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('/updateProfileImage', [UserController::class, 'updateProfileImage']);

    // User service package api
    Route::get('/getalluserservicepackage', [ServicePackages::class, 'getAllCoachServicePackage']);  //loggedin coach servicePackege      
    Route::post('/adduserservicepackage', [ServicePackages::class, 'addUserServicePackage']);
    Route::post('/addPackageRequest', [CochingRequestController::class, 'addPackageRequest']);

    // Master price get api
    Route::get('/getmastersessionformats', [MasterController::class, 'GetMasterSessionFormats']);
    Route::get('/getmastercancellationpolicies', [MasterController::class, 'GetMasterCancellationPolicies']);

    // VG Code login
    Route::post('/coachSendMessage', [SendCoachMessageController::class, 'coachSendMessage']);

    // Favorate coach
    Route::post('/addRemoveCoachFavorite', [FavoriteCoachController::class, 'addRemoveCoachFavorite']);
    Route::get('/coachFavoriteList', [FavoriteCoachController::class, 'coachFavoriteList']);

    // User reviews
    Route::get('/reviews', [ReviewController::class, 'reviews']);
    Route::post('/userReviews', [ReviewController::class, 'userReviews']);
    Route::post('/userReviewView', [ReviewController::class, 'userReviewView']);
    Route::put('/userReviewUpdate', [ReviewController::class, 'userReviewUpdate']);
    Route::post('/userReviewReply', [ReviewController::class, 'userReviewReply']);

    // Coach Reviews
    Route::post('/coachReviewsBackend', [ReviewController::class, 'coachReviewsBackend']);
    Route::post('/coachReviewView', [ReviewController::class, 'coachReviewView']);
    Route::put('/coachReviewUpdate', [ReviewController::class, 'coachReviewUpdate']);

    // Coach Reviews on Frontend
    Route::post('/coachReviewsFrontend', [ReviewController::class, 'coachReviewsFrontend']);

    // Coaching request
    Route::post('/cochingRequestSend', [CochingRequestController::class, 'cochingRequestSend']);
    Route::get('/cochingRequestsListsUserDashboard', [CochingRequestController::class, 'cochingRequestsListsUserDashboard']);

    //VG Code login

});