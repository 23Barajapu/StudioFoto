<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect /home based on auth status
Route::get('/home', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Requires Authentication)
Route::middleware(['auth', 'check.user.status'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
    });
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Web\CategoryController::class)->except(['show']);
    
    // Category Packages
    Route::prefix('categories/{category}')->name('categories.')->group(function () {
        Route::get('/packages', [\App\Http\Controllers\Web\CategoryController::class, 'packages'])->name('packages.index');
        Route::post('/packages', [\App\Http\Controllers\Web\CategoryController::class, 'addPackage'])->name('packages.store');
    });
    
    // Bookings Management
    Route::resource('bookings', BookingController::class)->only([
        'index', 'create', 'show'
    ]);
    
    // Payments Management
    Route::resource('payments', PaymentController::class)->only([
        'index', 'show'
    ]);
    
    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Update booking status
        Route::put('/bookings/{booking}/status', [BookingController::class, 'update'])->name('bookings.update');
        
        // Update payment status
        Route::put('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');
    });
});
