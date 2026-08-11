<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\AdminSourceController;
use App\Http\Controllers\AdminUserController;
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
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('submissions.show');
    Route::patch('/submissions/{submission}', [AdminSubmissionController::class, 'update'])->name('submissions.update');
    Route::post('/submissions/{submission}/resend', [AdminSubmissionController::class, 'resend'])->name('submissions.resend');
    Route::post('/submissions/{submission}/reply', [AdminSubmissionController::class, 'reply'])->name('submissions.reply');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/sources', [AdminSourceController::class, 'index'])->name('sources.index');
    Route::post('/sources', [AdminSourceController::class, 'store'])->name('sources.store');
    Route::get('/sources/{source}/edit', [AdminSourceController::class, 'edit'])->name('sources.edit');
    Route::patch('/sources/{source}/details', [AdminSourceController::class, 'updateDetails'])->name('sources.details.update');
    Route::patch('/sources/{source}', [AdminSourceController::class, 'update'])->name('sources.update');
    Route::get('/sources/{source}/template', [AdminSourceController::class, 'editTemplate'])->name('sources.template.edit');
    Route::patch('/sources/{source}/template', [AdminSourceController::class, 'updateTemplate'])->name('sources.template.update');
    Route::get('/sources/{source}/template/preview', [AdminSourceController::class, 'previewTemplate'])->name('sources.template.preview');
    Route::get('/sources/{source}/template/preview/email', [AdminSourceController::class, 'previewEmail'])->name('sources.template.preview.email');
});
