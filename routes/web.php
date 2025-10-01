<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // ========================================
    // Authentication Routes (No Auth Required)
    // ========================================
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    
    // Logout route - needs to be accessible
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // ========================================
    // Protected Admin Routes (Require Auth)
    // ========================================
    Route::middleware(['web', 'admin'])->group(function () {
        
        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // ========================================
        // Users Management
        // ========================================
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'createUser'])->name('users.create');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // ========================================
        // Sites Management
        // ========================================
        Route::get('/sites', [AdminController::class, 'sites'])->name('sites');
        Route::post('/sites', [AdminController::class, 'createSite'])->name('sites.create');
        Route::get('/sites/{id}', [AdminController::class, 'showSite'])->name('sites.show');
        Route::get('/sites/{id}/edit', [AdminController::class, 'editSite'])->name('sites.edit');
        Route::put('/sites/{id}', [AdminController::class, 'updateSite'])->name('sites.update');
        Route::delete('/sites/{id}', [AdminController::class, 'deleteSite'])->name('sites.delete');
        
        // ========================================
        // Guides Management
        // ========================================
        Route::get('/guides', [AdminController::class, 'guides'])->name('guides');
        Route::post('/guides', [AdminController::class, 'createGuide'])->name('guides.create');
        Route::get('/guides/{id}', [AdminController::class, 'showGuide'])->name('guides.show');
        Route::get('/guides/{id}/edit', [AdminController::class, 'editGuide'])->name('guides.edit');
        Route::put('/guides/{id}', [AdminController::class, 'updateGuide'])->name('guides.update');
        Route::post('/guides/{id}/approve', [AdminController::class, 'approveGuide'])->name('guides.approve');
        Route::delete('/guides/{id}', [AdminController::class, 'deleteGuide'])->name('guides.delete');
        
        // ========================================
        // Trips Management
        // ========================================
        Route::get('/trips', [AdminController::class, 'trips'])->name('trips');
        Route::get('/trips/{id}', [AdminController::class, 'showTrip'])->name('trips.show');
        Route::get('/trips/{id}/edit', [AdminController::class, 'editTrip'])->name('trips.edit');
        Route::put('/trips/{id}', [AdminController::class, 'updateTrip'])->name('trips.update');
        Route::delete('/trips/{id}', [AdminController::class, 'deleteTrip'])->name('trips.delete');
        
        // ========================================
        // Reviews Management
        // ========================================
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::get('/reviews/{id}', [AdminController::class, 'showReview'])->name('reviews.show');
        Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');
        
        // ========================================
        // Bookings Management
        // ========================================
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
        Route::put('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.updateStatus');
        Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');
    });
});