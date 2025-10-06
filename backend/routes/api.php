<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ContentBlockController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkProgramController; // <-- 1. Impor controller baru

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute untuk Page Content (Landing Hero)
Route::get('/page/{slug}', [PageContentController::class, 'show']);
Route::post('/page/{slug}', [PageContentController::class, 'update']);

// Rute untuk Features
Route::apiResource('features', FeatureController::class);

// Rute untuk Content Blocks
Route::get('/content-block/{key}', [ContentBlockController::class, 'show']);
Route::post('/content-block/{key}', [ContentBlockController::class, 'update']);

// Rute untuk Team Members
Route::apiResource('team-members', TeamMemberController::class);

// Rute untuk Profile
Route::get('/profile', [ProfileController::class, 'show']);
Route::post('/profile', [ProfileController::class, 'update']);

// 2. Tambahkan baris ini untuk mendaftarkan semua rute CRUD WorkProgram
Route::apiResource('work-programs', WorkProgramController::class);

