<?php

use Illuminate\Support\Facades\Route;
use Webkul\ApiResources\Http\Controllers\DocsController;

// Shop docs (accept with and without trailing slash)
Route::get('/api/shop', [DocsController::class, 'ui'])->defaults('area', '/api/shop');
Route::get('/api/shop/', [DocsController::class, 'ui'])->defaults('area', '/api/shop');
Route::get('/api/shop/openapi.json', [DocsController::class, 'json'])->defaults('area', '/api/shop');

// Admin docs (accept with and without trailing slash)
Route::get('/api/admin', [DocsController::class, 'ui'])->defaults('area', '/api/admin');
Route::get('/api/admin/', [DocsController::class, 'ui'])->defaults('area', '/api/admin');
Route::get('/api/admin/openapi.json', [DocsController::class, 'json'])->defaults('area', '/api/admin');
