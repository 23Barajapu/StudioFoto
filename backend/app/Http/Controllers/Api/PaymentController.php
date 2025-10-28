<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Create payment for booking
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:bank_transfer,credit_card,e_wallet,qris,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::with(['package', 'user'])->find($request->booking_id);

        // Check if user owns this booking
        if ($booking->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke booking ini',
            ], 403);
        }

        // Check if booking already has payment
        if ($booking->payment) {
            return response()->json([
                'success' => false,
                'message' => 'Booking ini sudah memiliki pembayaran',
            ], 422);
        }

        // Check booking status
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status booking tidak valid untuk pembayaran',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addHours(24),
            ]);

            // Jika cash, langsung set sebagai success (untuk admin)
            if ($request->payment_method === 'cash' && $request->user()->isAdmin()) {
                $payment->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);

                $booking->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            // TODO: Integrate with payment gateway (Midtrans) for other methods
            // For now, we'll create a simple payment record
            if ($request->payment_method !== 'cash') {
                // Simulate payment URL generation
                $payment->update([
                    'payment_url' => route('payment.show', $payment->id),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibuat',
                'data' => $payment->load('booking'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified payment
     */
    public function show(Request $request, $id)
    {
        $payment = Payment::with(['booking.package', 'booking.user'])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan',
            ], 404);
        }

        // Check authorization
        $user = $request->user();
        if ($user->isCustomer() && $payment->booking->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pembayaran ini',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    /**
     * Update payment status (Admin only or via callback)
     */
    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::with('booking')->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,success,failed,refunded',
            'transaction_id' => 'nullable|string',
            'payment_details' => 'nullable|array',
            'notes' => 'nullable|string',
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
            $data = $request->only(['status', 'transaction_id', 'payment_details', 'notes']);

            if ($request->status === 'success') {
                $data['paid_at'] = now();
                
                // Update booking status
                $payment->booking->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            $payment->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diperbarui',
                'data' => $payment->fresh()->load('booking'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Payment callback from payment gateway
     */
    public function callback(Request $request)
    {
        // TODO: Implement Midtrans callback handling
        // Verify signature, update payment status, etc.
        
        return response()->json([
            'success' => true,
            'message' => 'Callback received',
        ]);
    }

    /**
     * Get payment history
     */
    public function history(Request $request)
    {
        $user = $request->user();
        
        $query = Payment::with(['booking.package']);

        if ($user->isCustomer()) {
            $query->whereHas('booking', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->status($request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }
}
