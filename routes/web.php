<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;

// Redirect root to admin
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (accessible without authentication)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Protected admin routes (require authentication)
    Route::middleware(['web', 'admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Users Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'createUser'])->name('users.create');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Sites Management
        Route::get('/sites', [AdminController::class, 'sites'])->name('sites');
        Route::delete('/sites/{id}', [AdminController::class, 'deleteSite'])->name('sites.delete');
        
        // Guides Management
        Route::get('/guides', [AdminController::class, 'guides'])->name('guides');
        Route::post('/guides/{id}/approve', [AdminController::class, 'approveGuide'])->name('guides.approve');
        Route::delete('/guides/{id}', [AdminController::class, 'deleteGuide'])->name('guides.delete');
        
        // Trips Management
        Route::get('/trips', [AdminController::class, 'trips'])->name('trips');
        Route::delete('/trips/{id}', [AdminController::class, 'deleteTrip'])->name('trips.delete');
        
        // Reviews Management
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');
        
        // Bookings Management
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');
    });
});