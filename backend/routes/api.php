<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public galleries
Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/galleries/{id}', [GalleryController::class, 'show']);
Route::get('/galleries/categories/list', [GalleryController::class, 'categories']);

// Public reviews
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/average-rating', [ReviewController::class, 'averageRating']);

// Public schedules - check availability
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/check-availability', [ScheduleController::class, 'checkAvailability']);

// Payment callback (from payment gateway)
Route::post('/payments/callback', [PaymentController::class, 'callback']);

// Protected routes (requires authentication)
Route::middleware(['auth:sanctum', 'check.user.status'])->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/change-password', [AuthController::class, 'changePassword']);

    // Customer Dashboard
    Route::get('/dashboard', [DashboardController::class, 'customerDashboard']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/upcoming', [BookingController::class, 'upcoming']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Payments
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::get('/payments', [PaymentController::class, 'history']);

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{id}', [ReviewController::class, 'show']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    // Admin only routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        // Dashboard & Stats
        Route::get('/dashboard', [DashboardController::class, 'stats']);
        Route::get('/revenue-report', [DashboardController::class, 'revenueReport']);

        // Schedule Management
        Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
        Route::post('/schedules/generate', [ScheduleController::class, 'generateSchedules']);

        // Gallery Management
        Route::post('/galleries', [GalleryController::class, 'store']);
        Route::put('/galleries/{id}', [GalleryController::class, 'update']);
        Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);

        // Payment Management
        Route::put('/payments/{id}/status', [PaymentController::class, 'updateStatus']);

        // Review Management
        Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve']);
        Route::post('/reviews/{id}/reject', [ReviewController::class, 'reject']);

        // User Management
        Route::get('/users', function (Request $request) {
            $query = \App\Models\User::query();
            
            if ($request->has('role')) {
                $query->role($request->role);
            }
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            $users = $query->orderBy('created_at', 'desc')->paginate(15);
            
            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        });

        Route::put('/users/{id}/status', function ($id) {
            $user = \App\Models\User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan',
                ], 404);
            }
            
            $user->update(['is_active' => !$user->is_active]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah',
                'data' => $user,
            ]);
        });
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now(),
    ]);
});
