<?php

use Illuminate\Support\Facades\Route;
use Webkul\ApiResources\Http\Controllers\ShopAuthController;

// Public routes (no authentication required)
Route::post('/register', [ShopAuthController::class, 'register']);
Route::post('/login', [ShopAuthController::class, 'login']);
Route::post('/forgot-password', [ShopAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [ShopAuthController::class, 'resetPassword']);

// Protected routes (customer authentication required)
Route::middleware(['auth:sanctum', 'api.shop.auth'])->group(function () {
    Route::get('/profile', [ShopAuthController::class, 'profile']);
    Route::post('/update-profile', [ShopAuthController::class, 'updateProfile']);
    Route::post('/change-password', [ShopAuthController::class, 'changePassword']);
    Route::post('/logout', [ShopAuthController::class, 'logout']);
});
