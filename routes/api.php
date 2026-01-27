<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment_Methods\PaymeMerchantApiView;
use App\Http\Controllers\PaymentStatusController;


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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/delete-user', [App\Http\Controllers\ApiController::class, 'deleteUserFromDb'])->name('deleteUserFromDb');
Route::post("payment/payme/callback/", PaymeMerchantApiView::class)->name("payme:merchant");

// To'lov statusini tekshirish API'lari (mobil dastur uchun)
Route::post('/payment/check-status', [PaymentStatusController::class, 'checkPaymentStatus'])->name('payment.check-status');
Route::post('/payment/user-payments', [PaymentStatusController::class, 'getUserPayments'])->name('payment.user-payments');
