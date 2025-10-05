<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ContentBlockController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute untuk mengambil data konten (GET)
Route::get('/page/{slug}', [PageContentController::class, 'show']);

// Rute untuk menyimpan/memperbarui data konten (POST)
Route::post('/page/{slug}', [PageContentController::class, 'update']);

Route::apiResource('features', FeatureController::class);

// Rute untuk Content Blocks
Route::get('/content-block/{key}', [ContentBlockController::class, 'show']);
Route::post('/content-block/{key}', [ContentBlockController::class, 'update']);