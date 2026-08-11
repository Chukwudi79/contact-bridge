<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\AdminSourceController;
use App\Http\Middleware\EnsureAdmin;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/developers', function () {
    return view('developers');
})->name('developers');

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('throttle:admin-login')->name('admin.login.store');
Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

Route::middleware(EnsureAdmin::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::patch('/submissions/{submission}', [AdminSubmissionController::class, 'update'])->name('submissions.update');
    Route::get('/sources', [AdminSourceController::class, 'index'])->name('sources.index');
    Route::post('/sources', [AdminSourceController::class, 'store'])->name('sources.store');
    Route::patch('/sources/{source}', [AdminSourceController::class, 'update'])->name('sources.update');
});
