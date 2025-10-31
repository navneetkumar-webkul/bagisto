<?php

use Illuminate\Support\Facades\Route;
use Webkul\ApiResources\Http\Controllers\DocsController;

// Shop docs (accept with and without trailing slash)
Route::get('/api/v1/shop', [DocsController::class, 'ui'])->defaults('area', '/api/v1/shop');
Route::get('/api/v1/shop/', [DocsController::class, 'ui'])->defaults('area', '/api/v1/shop');
Route::get('/api/v1/shop/openapi.json', [DocsController::class, 'json'])->defaults('area', '/api/v1/shop');

// Admin docs (accept with and without trailing slash)
Route::get('/api/v1/admin', [DocsController::class, 'ui'])->defaults('area', '/api/v1/admin');
Route::get('/api/v1/admin/', [DocsController::class, 'ui'])->defaults('area', '/api/v1/admin');
Route::get('/api/v1/admin/openapi.json', [DocsController::class, 'json'])->defaults('area', '/api/v1/admin');
