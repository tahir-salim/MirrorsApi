<?php

use App\Events\MessageSend;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [HomeController::class, 'home']);
Route::get('/payment/booking-request/verify', [PaymentController::class, 'verifyTapPayment']);
Route::get('/payment/request-charges/verify', [PaymentController::class, 'verifyRequestChargesPayment']);
Route::get('tap/payment/failed', [PaymentController::class, 'showFailed']);
Route::get('tap/payment/success', [PaymentController::class, 'showSuccess']);

