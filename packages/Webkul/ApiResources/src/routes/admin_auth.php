<?php

use Illuminate\Support\Facades\Route;
use Webkul\ApiResources\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware(['auth:sanctum', 'api.auth'])->group(function () {
    Route::get('/get', [AuthController::class, 'get']);
    Route::post('/update', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
