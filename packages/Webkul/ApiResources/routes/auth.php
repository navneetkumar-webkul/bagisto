<?php

use Illuminate\Support\Facades\Route;
use Webkul\ApiResources\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
