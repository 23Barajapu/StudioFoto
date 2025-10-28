<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Booking::with(['package', 'schedule', 'user', 'payment']);

        // Filter by user for customers
        if ($user->isCustomer()) {
            $query->byUser($user->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->status($request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('booking_date', [$request->start_date, $request->end_date]);
        }

        // Search by booking code
        if ($request->has('search')) {
            $query->where('booking_code', 'like', "%{$request->search}%");
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'schedule_id' => 'required|exists:schedules,id',
            'customer_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Check if schedule is available
            $schedule = Schedule::find($request->schedule_id);
            
            if (!$schedule->isAvailable()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak tersedia',
                ], 422);
            }

            // Get package
            $package = Package::find($request->package_id);

            if (!$package->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paket tidak tersedia',
                ], 422);
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'package_id' => $package->id,
                'schedule_id' => $schedule->id,
                'booking_date' => $schedule->date,
                'booking_time' => $schedule->start_time,
                'total_price' => $package->price,
                'status' => 'pending',
                'customer_notes' => $request->customer_notes,
            ]);

            // Update schedule status
            $schedule->update(['status' => 'booked']);

            DB::commit();

            $booking->load(['package', 'schedule', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => $booking,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat booking',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified booking
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $booking = Booking::with(['package', 'schedule', 'user', 'payment', 'review'])
                          ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
            ], 404);
        }

        // Check authorization
        if ($user->isCustomer() && $booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke booking ini',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
            ], 404);
        }

        // Check authorization
        if ($user->isCustomer() && $booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke booking ini',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:pending,confirmed,paid,in_progress,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'customer_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [];

        // Only admin can update status and admin_notes
        if ($user->isAdmin()) {
            if ($request->has('status')) {
                $data['status'] = $request->status;
                
                if ($request->status === 'confirmed') {
                    $data['confirmed_at'] = now();
                } elseif ($request->status === 'cancelled') {
                    $data['cancelled_at'] = now();
                }
            }
            
            if ($request->has('admin_notes')) {
                $data['admin_notes'] = $request->admin_notes;
            }
        }

        // Customer can only update customer_notes
        if ($user->isCustomer() && $request->has('customer_notes')) {
            $data['customer_notes'] = $request->customer_notes;
        }

        $booking->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil diperbarui',
            'data' => $booking->fresh()->load(['package', 'schedule', 'user', 'payment']),
        ]);
    }

    /**
     * Cancel the specified booking
     */
    public function cancel(Request $request, $id)
    {
        $user = $request->user();
        
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan',
            ], 404);
        }

        // Check authorization
        if ($user->isCustomer() && $booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke booking ini',
            ], 403);
        }

        if (!$booking->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak dapat dibatalkan',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            // Free up the schedule
            $booking->schedule->update(['status' => 'available']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan',
                'data' => $booking->fresh(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan booking',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get upcoming bookings
     */
    public function upcoming(Request $request)
    {
        $user = $request->user();
        
        $query = Booking::with(['package', 'schedule'])->upcoming();

        if ($user->isCustomer()) {
            $query->byUser($user->id);
        }

        $bookings = $query->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }
}
