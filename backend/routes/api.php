<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller yang sudah ada
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ContentBlockController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkProgramController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Api\AspirationController;
use App\Http\Controllers\Api\AngkatanController;

// Controller API kita
use App\Http\Controllers\Api\EventController; 
use App\Http\Controllers\Api\CalendarActivityController; // <-- 1. TAMBAHKAN IMPORT INI

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Rute-rute lama Anda
Route::get('/page-content/{slug}', [PageContentController::class, 'show']);
Route::put('/page-content/{slug}', [PageContentController::class, 'update']);
Route::get('/content-blocks', [ContentBlockController::class, 'index']);
Route::post('/content-blocks/update-bulk', [ContentBlockController::class, 'updateBulk']);
Route::apiResource('features', FeatureController::class);
Route::get('/content-block/{key}', [ContentBlockController::class, 'show']);
Route::post('/content-block/{key}', [ContentBlockController::class, 'update']);

// Rute Team Member (dengan urutan benar)
Route::get('team-members/{teamMember}/photo', [TeamMemberController::class, 'servePhoto'])
     ->name('team-members.photo');
Route::get('team-members/{teamMember}/qrcode', [TeamMemberController::class, 'generateQrCode'])
     ->name('team-members.qrcode');
Route::apiResource('team-members', TeamMemberController::class);

Route::get('/profile', [ProfileController::class, 'show']);
Route::post('/profile', [ProfileController::class, 'update']);
Route::apiResource('work-programs', WorkProgramController::class);
Route::get('/articles/latest', [ArticleController::class, 'fetchLatest']);
Route::get('/all-articles', [ArticleController::class, 'fetchAll']);
Route::apiResource('articles', ArticleController::class);
Route::get('/work-programs-selection', [WorkProgramController::class, 'getAllForSelection']);


// ======================================================
// ==         RUTE BARU UNTUK ABSENSI DIGITAL          ==
// ======================================================

Route::get('admin/events/{event}/qrcode', [EventController::class, 'generateQrCode'])
     ->name('admin.events.qrcode');
Route::post('/admin/events/{event}/attend', [EventController::class, 'scanMemberAttendance']);
Route::post('/settings/background', [SettingController::class, 'updateBackground']);
Route::get('/settings/background', [SettingController::class, 'getBackground']);
Route::post('admin/events/{event}/scan-member', [EventController::class, 'scanMemberAttendance'])
     ->name('admin.events.scan-member');
Route::apiResource('admin/events', EventController::class);


// ======================================================
// ==         RUTE BARU UNTUK KOTAK ASPIRASI           ==
// ======================================================

// Rute Publik (Untuk siswa mengirim aspirasi)
Route::post('/aspirations', [AspirationController::class, 'store']);
Route::get('/aspirations/track/{ticket_id}', [AspirationController::class, 'track']);
Route::get('/aspirations/public', [AspirationController::class, 'getPublicAspirations']);

// Rute Admin (Untuk mengelola aspirasi di dasbor)
Route::prefix('admin')->group(function () {
    Route::get('/aspirations', [AspirationController::class, 'index']);
    Route::get('/aspirations/{aspiration}', [AspirationController::class, 'show']);
    Route::put('/aspirations/{aspiration}', [AspirationController::class, 'update']);
    Route::delete('/aspirations/{aspiration}', [AspirationController::class, 'destroy']);
});


// ======================================================
// ==       RUTE BARU UNTUK KALENDER KEGIATAN          ==
// ======================================================

Route::get('/calendar-activities', [CalendarActivityController::class, 'indexPublic']);

// Rute Admin (CRUD untuk mengelola jadwal)
Route::prefix('admin')->group(function () {
    Route::apiResource('calendar-activities', CalendarActivityController::class);
});

Route::apiResource('angkatans', AngkatanController::class);
// ======================================================
// ==              AKHIR RUTE BARU                     ==
// ======================================================