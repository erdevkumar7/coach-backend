<?php

use App\Http\Controllers\Api\CalendarBookingController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\FavoriteCoachController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SendCoachMessageController;
use App\Http\Controllers\Api\ServicePackages;
use App\Http\Controllers\Api\SimilarCoachesController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\SupportRequestController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CochingRequestController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\GoogleLoginController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\UserDashboardController;








Route::get('/status', function () {
    return response()->json(['status' => 'API is working']);
});
Route::post('/contact-message', [ContactMessageController::class, 'store']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



// Forgot Password.
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/verify-reset-token/{token}', [AuthController::class, 'verifyResetToken']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);




//google login
// Route::get('auth/google/callback', [GoogleLoginController::class, 'callback']);
// Route::get('auth/google/redirect/user', [GoogleLoginController::class, 'redirect']);
// Route::get('auth/google/callback/{user_type?}', [GoogleLoginController::class, 'callback'])->name('google.callback');
Route::get('auth/google/redirect/{userType?}', [GoogleLoginController::class, 'redirect']);
Route::get('auth/google/callback', [GoogleLoginController::class, 'callback'])->name('google.callback');

Route::post('/featuredCoachList', [AuthController::class, 'featuredCoachList']);
Route::post('/coachlist', [AuthController::class, 'coachlist']);
Route::post('/coachDetails', [AuthController::class, 'coachDetails']);

Route::get('/getallmastercategories', [GuestController::class, 'getallmastercategories']);

Route::post('/getCountries', [GuestController::class, 'getAllCountries']);
Route::post('/getStates/{country_id}', [GuestController::class, 'getStateOfaCountry']);
Route::post('/getCities/{state_id}', [GuestController::class, 'getCitiesOfaState']);
Route::post('/getStatesOrCities', [GuestController::class, 'getStatesOrCities']);

Route::post('/getDeliveryMode', [GuestController::class, 'deliveryAllMode']);
Route::post('/getLanguages', [GuestController::class, 'getAllLanguages']);
Route::post('/ageGroups', [GuestController::class, 'getAllAgeGroup']);
Route::post('/coachingCategories', [GuestController::class, 'getAllCoachingCategories']);
Route::post('/sessionFormats', [GuestController::class, 'getAllSessionFormats']);
Route::post('/priceModels', [GuestController::class, 'getAllPriceModels']);

Route::post('/getCoachType', [GuestController::class, 'getAllCoachType']);
Route::post('/getSubCoachType/{coach_type_id?}', [GuestController::class, 'getAllSubCoachType']);
Route::post('/getAllCoachServices', [GuestController::class, 'getAllCoachServices']);
// Route::post('/getcoachprofile', [AuthController::class, 'getcoachprofile']);


// VG Routes start
Route::post('/getMasterBudgetRange', [MasterController::class, 'getMasterBudgetRange']);
Route::post('/coachExperienceLevel', [MasterController::class, 'coachExperienceLevel']);
Route::post('/communicationChannels', [MasterController::class, 'communicationChannels']);
Route::post('/urgencyStartDate', [MasterController::class, 'urgencyStartDate']);


Route::get('/getmasterprices', [MasterController::class, 'GetMasterPrices']);
Route::post('/getmasterblogs', [MasterController::class, 'GetMasterBlogs']);
Route::post('/similarcoaches', [SimilarCoachesController::class, 'SimilarCoaches']);
Route::get('/subscriptionplans', [SubscriptionPlanController::class, 'SubscriptionPlans']);
Route::get('/subscriptionplansbyduration', [SubscriptionPlanController::class, 'SubscriptionPlansByDuration']);
Route::get('/getfaqs', [FaqController::class, 'Getfaqs']);

Route::post('/coachCalendarBookingDetails', [CalendarBookingController::class, 'coachCalendarBookingDetails']);
Route::post('/date_time_avalibility', [ServicePackages::class, 'date_time_avalibility']);


// Availble and unavailable data show in calendar
Route::post('/calendar-status', [CalendarBookingController::class, 'calendarStatus']);


// VG Routes end
Route::get('email/changeStatus', [AuthController::class, 'change_user_status']);
Route::post('/getuserservicepackage/{id}', [ServicePackages::class, 'getUserServicePackage']);
Route::post('/getServicePackageById/{coach_id}/{package_id}', [ServicePackages::class, 'getServicePackageById']);
Route::post('/get_AarrayOfServicePackageIds_ByCoachId/{coach_id}', [ServicePackages::class, 'getAarrayOfServicePackageIdsByCoachId']);
Route::post('/getmastercancellationpolicies', [MasterController::class, 'GetMasterCancellationPolicies']);
// Coach Reviews on Frontend
Route::post('/coachReviewsFrontend', [ReviewController::class, 'coachReviewsFrontend']);

//Stripe payment
Route::get('/stripe/packages/success/{session_id}', [StripeController::class, 'userPackageSuccess']);

Route::get('/stripe/Coachpackages/success/{session_id}', [StripeController::class, 'CoachPackageSuccess']);




Route::middleware('auth:api')->group(function () {
    Route::post('/validateToken', [AuthController::class, 'validateToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/getuserprofile', [AuthController::class, 'getuserprofile']);
    Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('/updateUserProfile', [AuthController::class, 'updateUserProfile']);
    Route::post('/updateProfileImage', [UserController::class, 'updateProfileImage']);

    // User service package api
    Route::get('/getalluserservicepackage', [ServicePackages::class, 'getAllCoachServicePackage']);  //loggedin coach servicePackege
  //loggedin coach servicePackege
    Route::post('/adduserservicepackage', [ServicePackages::class, 'addServicePackage']);
    Route::post('/getServicePackageByIDForUpdate', [ServicePackages::class, 'getServicePackageByIDForUpdate']);
    Route::post('/update-service-package', [ServicePackages::class, 'updateServicePackage']);
    Route::post('/addPackageRequest', [CochingRequestController::class, 'addPackageRequest']);

    Route::delete('/delete-package', [ServicePackages::class, 'deletePackageRequest']);


    // Master price get api
    Route::get('/getmastersessionformats', [MasterController::class, 'GetMasterSessionFormats']);

    // VG Code login
    Route::post('/coachSendMessage', [SendCoachMessageController::class, 'coachSendMessage']);


    Route::post('/calendar-status-dashboard', [CalendarBookingController::class, 'calendarStatusDashboard']);
    Route::get('/getusergoals', [AuthController::class, 'getusergoals']);

    // Favorate coach
    Route::post('/addRemoveCoachFavorite', [FavoriteCoachController::class, 'addRemoveCoachFavorite']);
    Route::get('/coachFavoriteList', [FavoriteCoachController::class, 'coachFavoriteList']);

    Route::post('/addsupportrequest', [SupportRequestController::class, 'AddSupportRequest']);

    // User reviews
    Route::get('/reviews', [ReviewController::class, 'reviews']);
    Route::post('/userReviewSubmit', [ReviewController::class, 'userReviewSubmit']);
    Route::post('/userReviews', [ReviewController::class, 'userReviews']);
    Route::post('/userReviewView', [ReviewController::class, 'userReviewView']);
    Route::put('/userReviewUpdate', [ReviewController::class, 'userReviewUpdate']);
    Route::delete('/userReviewDelete/{id}', [ReviewController::class, 'userReviewDelete']);


    // Coach Reviews
    Route::get('/coachReviewsBackend', [ReviewController::class, 'coachReviewsBackend']);
    Route::post('/coachReplyToUserReview', [ReviewController::class, 'coachReplyToUserReview']);
    Route::post('/coachReviewView', [ReviewController::class, 'coachReviewView']);
    Route::put('/coachReviewStatusUpdate', [ReviewController::class, 'coachReviewStatusUpdate']);



    // Coaching request
    Route::post('/cochingRequestSend', [CochingRequestController::class, 'cochingRequestSend']);
    Route::get('/cochingRequestsListsUserDashboard', [CochingRequestController::class, 'cochingRequestsListsUserDashboard']);

    //coach activities
    Route::post('/getPendingCoaching', [SimilarCoachesController::class, 'getPendingCoaching']);
    Route::post('/getCoachingPackages', [SimilarCoachesController::class, 'getCoachingPackages']);
    Route::post('/getPackagesCompleted', [SimilarCoachesController::class, 'getPackagesCompleted']);

    //chat app
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::post('/chat/getMessages', [ChatController::class, 'getMessages']);
    Route::post('/generalCoachChatList', [ChatController::class, 'generalCoachChatList']);

    //Dashboard
    Route::post('/coachDashboard', [UserController::class, 'coachDashboard']);

    //transaction
    Route::post('/transaction_detail', [ServicePackages::class, 'transaction_detail']);

    //stripePayment
    Route::post('/payServicePackages', [StripeController::class, 'payServicePackages']);

    Route::post('/PayCoachSubcriptionPlan', [StripeController::class, 'PayCoachSubcriptionPlan']);

    Route::get('/getCoachSubcriptionPlan', [CalendarController::class, 'getCoachSubcriptionPlan']);

    Route::post('/CoachConfirmedBooking', [CalendarController::class, 'CoachConfirmedBooking']);

    Route::post('/bookingRescheduleByUser', [CalendarController::class, 'bookingRescheduleByUser']);

    Route::post('/change-password', [AuthController::class, 'change_password']);
    Route::post('/setting', [AuthController::class, 'setting']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
    Route::get('/getsetting', [AuthController::class, 'getsetting']);
    Route::post('/UserConfirmedBooking', [CalendarController::class, 'UserConfirmedBooking']);
    Route::post('/change-booking-status', [CalendarController::class, 'ChangeBookingStatus']);

    Route::get('/CoachplanStatus', [CalendarController::class, 'CoachplanStatus']);

    Route::get('/user-request-count', [UserDashboardController::class, 'UserRequestCount']);
    Route::get('/user-coaching-status-count', [UserDashboardController::class, 'UserCoachingStatusCount']);
});
