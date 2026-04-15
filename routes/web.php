<?php

use Illuminate\Support\Facades\Route;

// Landing Page - Single Entry Point (SPA Style)
Route::get('/', function () {
    return view('modules.landing.home');
})->name('landing.home');

// Optional: API routes for dynamic content (future)
// Route::prefix('api/v1')->group(function () {
//     Route::get('/news', [NewsController::class, 'index']);
//     Route::get('/desa', [DesaController::class, 'index']);
//     Route::get('/documents', [DocumentController::class, 'index']);
// });