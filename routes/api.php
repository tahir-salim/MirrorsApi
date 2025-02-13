<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TalentApi\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TalentApi\RequestCommentController as TalentRequestCommentController;
use App\Http\Controllers\TalentApi\RequestController as TalentRequestController;
use App\Http\Controllers\TalentApi\TalentPostController;
use App\Http\Controllers\RequestCommentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestAttachmentController;
use App\Http\Controllers\RequestChargesController;
use App\Http\Controllers\TalentApi\DashboardController as TalentDashboardController;
use App\Http\Controllers\TalentApi\PackageController;
use App\Http\Controllers\TalentApi\ScheduleController;
use App\Http\Controllers\TalentController;
use App\Models\RequestAttachment;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// MobileSignUp
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [SignupController::class, 'store']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/otp/send', [AuthController::class, 'sendOtp']);
Route::post('/social-login', [AuthController::class, 'socialLogin']);
Route::post('/social-login/otp/send', [AuthController::class, 'socialLoginPhoneVerification']);

// category apis
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{categoryID}/talents', [CategoryController::class, 'showTalents']);
Route::get('/search', [CategoryController::class, 'searchResults']);
// talent apis
Route::get('talents/services', [ServiceController::class, 'index']);
Route::get('talents/list', [TalentController::class, 'talentsList']);
Route::get('/talents/{talentID}/details', [TalentController::class, 'showTalent']);
Route::get('talent/availability', [RequestController::class, 'getMonthSchedule']);
Route::middleware('auth:sanctum')->group(function () {

    // Logout api
    Route::post('/logout', [AuthController::class, 'logOut']);

    // user dashboard apis
    Route::get('/user/init', [DashboardController::class, 'init']);

    // user controller apis
    Route::post('/user/profile', [UserController::class, 'updateUser']);
    Route::post('/user/reset-password', [UserController::class, 'resetPassword']);
    Route::post('/user/upload-avatar', [UserController::class, 'uploadProfileImage']);

    // talent dashboard apis
    Route::get('/talent/init', [TalentDashboardController::class, 'init']);

    // notification apis
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/read', [NotificationController::class, 'readNotifications']);

    // talent apis
    Route::post('/talent/profile', [UserController::class, 'updateTalent']);
    Route::get('/talent/requests/completed', [TalentRequestController::class, 'getTalentCompletedRequests']);
    Route::get('/talent/requests/ongoing', [TalentRequestController::class, 'getTalentRequests']);
    Route::get('/talent/requests/accepted', [TalentRequestController::class, 'getTalentAcceptedRequests']);
    Route::get('/talent/requests/pending', [TalentRequestController::class, 'getTalentPendingRequests']);
    Route::post('/talent/requests/{requestID}/comment', [TalentRequestCommentController::class, 'store']);
    Route::post('/talent/requests/{requestID}/accept', [TalentRequestController::class, 'acceptRequest']);
    Route::post('/talent/requests/{requestID}/reject', [TalentRequestController::class, 'rejectRequest']);
    Route::post('talent/requests/{requestID}/complete', [TalentRequestController::class, 'completeRequest']);
    Route::post('/talent/services/add', [ServiceController::class, 'storeService']);
    Route::post('/talent/services/{serviceID}/update', [ServiceController::class, 'updateService']);
    Route::post('/talent/services/{serviceID}/remove', [ServiceController::class, 'removeService']);
    Route::post('/talent/packages/add', [PackageController::class,  'storePackage']);
    Route::post('/talent/packages/{packageID}/remove', [PackageController::class,  'removePackage']);
    Route::post('/talent/packages/{packageID}/update', [PackageController::class,  'updatePackage']);
    Route::post('/talent/post/create', [TalentPostController::class, 'store']);
    Route::post('/talent/posts/{postID}/delete', [TalentPostController::class, 'delete']);
    Route::post('/talent/posts/{postID}/update', [TalentPostController::class, 'update']);
    Route::post('/talent/schedule/update', [ScheduleController::class, 'store']);

    // user booking request apis
    Route::get('/user/requests/ongoing', [RequestController::class, 'getUserRequests']);
    Route::get('/user/requests/rejected', [RequestController::class, 'getRejectedRequests']);
    Route::get('/user/requests/pending', [RequestController::class, 'getPendingRequests']);
    Route::get('/user/requests/accepted', [RequestController::class, 'getAcceptedRequests']);
    Route::get('/user/requests/completed', [RequestController::class, 'getUserCompletedRequests']);
    Route::post('/user/requests/{requestID}/cancel', [RequestController::class, 'cancelRequest']);
    Route::post('/user/requests/{requestID}/comment', [RequestCommentController::class, 'store']);
    Route::post('/user/requests/{requestID}/review', [RequestController::class, 'reviewRequest']);
    Route::post('/user/talents/{talentID}/request', [RequestController::class, 'storeRequest']);
    Route::post('/user/requests/{requestID}/update', [RequestController::class, 'updateRequest']);
    Route::post('/user/requests/{requestID}/payment', [RequestController::class, 'paymentRequests']);
    Route::post('/user/requests/{requestID}/attachment', [RequestAttachmentController::class, 'store']);

    // user apis
    Route::post('/user/update/categories', [UserController::class, 'updateCategories']);

    //request charges apis
    Route::post('/request/charges/add', [RequestChargesController::class, 'storeRequestCharges']);
    Route::post('/request/charges/{requestChargesID}/payment', [RequestChargesController::class, 'paymentRequestCharges']);


    // user messages
    Route::post('/send/message', [ChatController::class, 'sendMessage']);
    Route::get('/chats/{chatID}/messages', [ChatController::class, 'fetchMessages']);
    Route::get('/chats', [ChatController::class, 'fetchChats']);
});
