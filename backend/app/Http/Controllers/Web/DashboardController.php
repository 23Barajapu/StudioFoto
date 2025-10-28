<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        // Get statistics
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $totalBookings = Booking::count();
        $pendingPayments = Booking::where('status', 'pending')->count();

        $stats = [
            'total_revenue' => $totalRevenue,
            'formatted_revenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            'total_bookings' => $totalBookings,
            'pending_payments' => $pendingPayments,
        ];

        // Get recent bookings
        $recentBookings = Booking::with(['user', 'package', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentBookings'));
    }
}
