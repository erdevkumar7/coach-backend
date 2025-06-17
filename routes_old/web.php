<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MasterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});


Route::any('/admin', [AdminController::class, 'login'])->name('admin.login');
Route::any('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/admin/getstate', [AdminController::class, 'getstate']);
Route::post('/admin/getcity', [AdminController::class, 'getcity']);
Route::post('/admin/getsubType', [AdminController::class, 'getsubType']);



// Group all protected admin routes under middleware
Route::middleware(['auth:admin', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/userList', [UserManagementController::class, 'userList'])->name('admin.userList');
    Route::any('/admin/addUser/{id?}', [UserManagementController::class, 'addUser'])->name('admin.addUser');
    Route::get('/admin/coachList', [UserManagementController::class, 'coachList'])->name('admin.coachList');
    Route::any('/admin/addCoach/{id?}', [UserManagementController::class, 'addCoach'])->name('admin.addCoach');
    Route::post('/admin/update_status', [UserManagementController::class, 'updateUserStatus']);
    Route::post('/admin/delete_user', [UserManagementController::class, 'deleteUser']);
    Route::get('/admin/coachProfile/{id?}', [UserManagementController::class, 'coachProfile'])->name('admin.coachProfile');
    Route::get('/admin/viewCoach/{id}', [UserManagementController::class, 'viewCoach'])->name('admin.viewCoach');
    Route::get('/admin/viewUser/{id}', [UserManagementController::class, 'viewUser'])->name('admin.viewUser');
    Route::post('/admin/user/bulk-delete-user', [UserManagementController::class, 'bulkDeleteusr'])->name('admin.bulkDeleteusr');
    Route::post('/admin/coach/bulk-delete-coach', [UserManagementController::class, 'bulkDeleteCoach'])->name('admin.bulkDeleteCoach');

    Route::any('/admin/addProfessional/{id?}', [UserManagementController::class, 'addProfessional'])->name('admin.addProfessional');
    Route::post('/admin/deleteDocument', [UserManagementController::class, 'deleteDocument'])->name('admin.deleteDocument');

    Route::get('/admin/languageList', [MasterController::class, 'languageList'])->name('admin.languageList');
    Route::any('/admin/addLanguage/{id?}', [MasterController::class, 'addLanguage'])->name('admin.addLanguage');    
    Route::post('/admin/update_lang_status', [MasterController::class, 'updateLanguageStatus']);
    Route::post('/admin/language/bulk-delete-language', [MasterController::class, 'bulkDeleteLanguage'])->name('admin.bulkDeleteLanguage');
    

    Route::get('/admin/serviceList', [MasterController::class, 'serviceList'])->name('admin.serviceList');
    Route::any('/admin/addService/{id?}', [MasterController::class, 'addService'])->name('admin.addService');    
    Route::post('/admin/update_service_status', [MasterController::class, 'updateServiceStatus']);
    Route::post('/admin/service/bulk-delete', [MasterController::class, 'bulkDeleteService'])->name('admin.bulkDeleteService');

    Route::get('/admin/subscriptionList', [MasterController::class, 'subscriptionList'])->name('admin.subscriptionList');
    Route::any('/admin/addSubscription/{id?}', [MasterController::class, 'addSubscription'])->name('admin.addSubscription');
    Route::any('/admin/update_subscri_status/{id?}', [MasterController::class, 'update_subscri_status'])->name('admin.update_subscri_status');
    Route::post('/admin/plan/bulk-delete', [MasterController::class, 'bulkDeletePlan'])->name('admin.bulkDeletePlan');

    Route::any('/admin/addPolicy/{id?}', [MasterController::class, 'addPolicy'])->name('admin.addPolicy');
    Route::get('/admin/policyList', [MasterController::class, 'policyList'])->name('admin.policyList');
    Route::get('/admin/viewPolicy/{id}', [MasterController::class, 'viewPolicy'])->name('admin.viewPolicy');
    Route::post('/admin/deletePolicy', [MasterController::class, 'deletePolicy'])->name('admin.deletePolicy');
    Route::post('/admin/policy/bulk-delete', [MasterController::class, 'bulkDeletePolicy'])->name('admin.bulkDeletePolicy');

    

    Route::any('/admin/addCoachType/{id?}', [MasterController::class, 'addCoachType'])->name('admin.addCoachType');
    Route::get('/admin/coachTypeList', [MasterController::class, 'coachTypeList'])->name('admin.coachTypeList');
    Route::post('/admin/update_type_status', [MasterController::class, 'updateTypeStatus']);
    Route::any('/admin/addCoachSubType/{id?}', [MasterController::class, 'addCoachSubType'])->name('admin.addCoachSubType');
    Route::get('/admin/coachSubTypeList/{id?}', [MasterController::class, 'coachSubTypeList'])->name('admin.coachSubTypeList');
    Route::post('/admin/update_subtype_status', [MasterController::class, 'updateSubTypeStatus']);
    Route::post('/admin/coachCat/bulk-delete', [MasterController::class, 'bulkDeleteCoachCat'])->name('admin.bulkDeleteCoachCat');
    Route::post('/admin/coachSubCat/bulk-delete', [MasterController::class, 'bulkDeleteCoachSubCat'])->name('admin.bulkDeleteCoachSubCat');

    

    Route::any('/admin/addCoachingCategory/{id?}', [MasterController::class, 'addCoachingCategory'])->name('admin.addCoachingCategory');
    Route::get('/admin/coachingCategoryList', [MasterController::class, 'coachingCategoryList'])->name('admin.coachingCategoryList');
    Route::post('/admin/update_category_status', [MasterController::class, 'updateCategoryStatus']);
    Route::post('/admin/category/bulk-delete', [MasterController::class, 'bulkDeletecat'])->name('admin.bulkDeletecat');

    Route::get('/admin/enquiryList', [MasterController::class, 'enquiryList'])->name('admin.enquiryList');
    Route::get('/admin/viewEnquiry', [MasterController::class, 'viewEnquiry'])->name('admin.viewEnquiry');

    Route::any('/admin/addDeliveryMode/{id?}', [MasterController::class, 'addDeliveryMode'])->name('admin.addDeliveryMode');
    Route::get('/admin/deliveryModeList', [MasterController::class, 'deliveryModeList'])->name('admin.deliveryModeList');
    Route::post('/admin/update_mode_status', [MasterController::class, 'updateModeStatus']);
    Route::post('/admin/mode/bulk-delete', [MasterController::class, 'bulkDeleteMode'])->name('admin.bulkDeleteMode');

    Route::get('/admin/blogList', [MasterController::class, 'blogList'])->name('admin.blogList');
    Route::any('/admin/addBlog/{id?}', [MasterController::class, 'addBlog'])->name('admin.addBlog');   
    Route::post('/admin/update_blog_status', [MasterController::class, 'updateBlogStatus']);
    Route::post('/admin/blog/bulk-delete', [MasterController::class, 'bulkDeleteBlog'])->name('admin.bulkDeleteBlog');

    Route::any('/admin/addEmailTemplate/{id?}', [MasterController::class, 'addEmailTemplate'])->name('admin.addEmailTemplate');   
});