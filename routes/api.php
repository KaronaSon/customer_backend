<?php

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\OrderItemApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/products', ProductApiController::class);
Route::get('/products/{pid}/image', [ProductApiController::class, 'getImage']);


Route::post('/customer/auth', [CustomerApiController::class, 'auth']);         // Email & password login/register, send OTP
Route::post('/customer/verify', [CustomerApiController::class, 'verify']);     // Verify OTP
Route::post('/customer/setup', [CustomerApiController::class, 'setup']);       // Fill customer info
Route::get('/customer/show', [CustomerApiController::class, 'show']);          // Show profile by email (?email=...)
Route::post('/customer/photo', [CustomerApiController::class, 'updatePhoto']); // Update only profile photo
Route::post('/customer/login', [CustomerApiController::class, 'login']);
Route::post('/customer/request-reset-password', [CustomerApiController::class, 'requestResetPassword']);
Route::post('/customer/verify-reset-otp', [CustomerApiController::class, 'verifyResetOtp']);
Route::post('/customer/reset-password', [CustomerApiController::class, 'resetPassword']);
Route::post('/test-email', [CustomerApiController::class, 'testEmail']);

Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist']);
Route::post('/orders/place', [OrderController::class, 'placeOrder']);

// Order endpoints
Route::post('/orders/place', [OrderApiController::class, 'placeOrder']);
Route::get('/orders', [OrderApiController::class, 'index']);
Route::get('/orders/customer/{customer_id}', [OrderApiController::class, 'listByCustomer']);
Route::get('/orders/{oid}', [OrderApiController::class, 'show']);

// Order item endpoints (if implemented)
Route::get('orders/{order_id}/items', [OrderItemApiController::class, 'index']);
Route::post('orders/{order_id}/items', [OrderItemApiController::class, 'store']);
Route::put('orders/{order_id}/items/{item_id}', [OrderItemApiController::class, 'update']);
Route::delete('orders/{order_id}/items/{item_id}', [OrderItemApiController::class, 'destroy']);