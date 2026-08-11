<?php

use App\Http\Controllers\ContactSubmissionController;
use App\Http\Middleware\EnsureAllowedContactOrigin;
use Illuminate\Support\Facades\Route;

Route::options('/contact', fn () => response()->noContent())
    ->middleware(EnsureAllowedContactOrigin::class);

Route::post('/contact', [ContactSubmissionController::class, 'store'])
    ->middleware([EnsureAllowedContactOrigin::class, 'throttle:contact'])
    ->name('contact.store');