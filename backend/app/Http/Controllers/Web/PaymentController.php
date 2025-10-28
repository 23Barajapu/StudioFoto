<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.package']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($qb) use ($search) {
                      $qb->where('booking_code', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.package', 'booking.schedule']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,success,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
        ];

        if ($request->has('notes')) {
            $data['notes'] = $request->notes;
        }

        if ($request->status === 'success') {
            $data['paid_at'] = now();

            // Update booking status
            $payment->booking->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        $payment->update($data);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Status pembayaran berhasil diperbarui');
    }
}
