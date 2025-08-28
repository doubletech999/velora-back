<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\GuideController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Sites routes
    Route::apiResource('sites', SiteController::class);
    
    // Guides routes
    Route::apiResource('guides', GuideController::class);
    
    // Trips routes
    Route::apiResource('trips', TripController::class);
    
    // Reviews routes
    Route::apiResource('reviews', ReviewController::class);
    
    // Bookings routes
    Route::apiResource('bookings', BookingController::class);
});
