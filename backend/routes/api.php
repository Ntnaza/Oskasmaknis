<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ContentBlockController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkProgramController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute ini sudah ada, kita biarkan saja
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Rute untuk Page Content (Landing Hero, Judul Halaman Index)
// DIGANTI: dari '/page/{slug}' menjadi '/page-content/{slug}' agar cocok dengan frontend
// DIGANTI: dari 'post' menjadi 'put' untuk update
Route::get('/page-content/{slug}', [PageContentController::class, 'show']);
Route::put('/page-content/{slug}', [PageContentController::class, 'update']);


// Rute untuk Content Blocks
// DITAMBAHKAN: Rute-rute ini dibutuhkan oleh Pinia store kita
Route::get('/content-blocks', [ContentBlockController::class, 'index']);
Route::post('/content-blocks/update-bulk', [ContentBlockController::class, 'updateBulk']);


// Rute-rute lama Anda yang mungkin masih digunakan di bagian lain
// Kita biarkan saja agar tidak merusak fungsionalitas yang sudah ada
Route::apiResource('features', FeatureController::class);
Route::get('/content-block/{key}', [ContentBlockController::class, 'show']);
Route::post('/content-block/{key}', [ContentBlockController::class, 'update']);
Route::apiResource('team-members', TeamMemberController::class);
Route::get('/profile', [ProfileController::class, 'show']);
Route::post('/profile', [ProfileController::class, 'update']);
// ... rute lain ...
Route::apiResource('work-programs', WorkProgramController::class);

// Rute Spesifik untuk Artikel
Route::get('/articles/latest', [ArticleController::class, 'fetchLatest']);
Route::get('/all-articles', [ArticleController::class, 'fetchAll']);

// Rute Umum untuk Artikel (setelah rute spesifik)
Route::apiResource('articles', ArticleController::class);

Route::get('/work-programs-selection', [WorkProgramController::class, 'getAllForSelection']);