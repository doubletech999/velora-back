<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', [AdminController::class, 'dashboard']);

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/sites', [AdminController::class, 'sites'])->name('sites');
    Route::get('/guides', [AdminController::class, 'guides'])->name('guides');
    Route::get('/trips', [AdminController::class, 'trips'])->name('trips');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
});
