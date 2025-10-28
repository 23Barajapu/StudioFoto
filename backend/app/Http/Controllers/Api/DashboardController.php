<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Review;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics (Admin only)
     */
    public function stats()
    {
        // Total counts
        $totalBookings = Booking::count();
        $totalCustomers = User::role('customer')->count();
        $totalRevenue = Payment::success()->sum('amount');
        $averageRating = Review::approved()->avg('rating');

        // This month stats
        $thisMonthBookings = Booking::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();
        
        $thisMonthRevenue = Payment::success()
                                  ->whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->sum('amount');

        // Pending bookings
        $pendingBookings = Booking::pending()->count();

        // Upcoming bookings (next 7 days)
        $upcomingBookings = Booking::whereBetween('booking_date', [
                                      Carbon::today(),
                                      Carbon::today()->addDays(7)
                                  ])
                                  ->whereIn('status', ['confirmed', 'paid'])
                                  ->count();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'package', 'schedule'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        // Popular packages
        $popularPackages = Package::withCount('bookings')
                                 ->orderBy('bookings_count', 'desc')
                                 ->limit(5)
                                 ->get();

        // Monthly revenue chart (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::success()
                             ->whereMonth('created_at', $date->month)
                             ->whereYear('created_at', $date->year)
                             ->sum('amount');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        }

        // Booking status distribution
        $bookingStatusDistribution = Booking::select('status', DB::raw('COUNT(*) as count'))
                                           ->groupBy('status')
                                           ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_bookings' => $totalBookings,
                    'total_customers' => $totalCustomers,
                    'total_revenue' => (float) $totalRevenue,
                    'average_rating' => round($averageRating, 2),
                    'this_month_bookings' => $thisMonthBookings,
                    'this_month_revenue' => (float) $thisMonthRevenue,
                    'pending_bookings' => $pendingBookings,
                    'upcoming_bookings' => $upcomingBookings,
                ],
                'recent_bookings' => $recentBookings,
                'popular_packages' => $popularPackages,
                'monthly_revenue' => $monthlyRevenue,
                'booking_status_distribution' => $bookingStatusDistribution,
            ],
        ]);
    }

    /**
     * Get customer dashboard
     */
    public function customerDashboard(Request $request)
    {
        $user = $request->user();

        // User's bookings count
        $totalBookings = Booking::byUser($user->id)->count();
        
        // Upcoming bookings
        $upcomingBookings = Booking::byUser($user->id)
                                  ->upcoming()
                                  ->with(['package', 'schedule'])
                                  ->get();

        // Recent bookings
        $recentBookings = Booking::byUser($user->id)
                                ->with(['package', 'schedule', 'payment'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        // Pending payments
        $pendingPayments = Payment::whereHas('booking', function($query) use ($user) {
                                  $query->where('user_id', $user->id);
                              })
                              ->pending()
                              ->with('booking.package')
                              ->get();

        // Bookings that can be reviewed
        $canBeReviewed = Booking::byUser($user->id)
                               ->status('completed')
                               ->whereDoesntHave('review')
                               ->with('package')
                               ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_bookings' => $totalBookings,
                    'upcoming_bookings_count' => $upcomingBookings->count(),
                    'pending_payments_count' => $pendingPayments->count(),
                    'can_be_reviewed_count' => $canBeReviewed->count(),
                ],
                'upcoming_bookings' => $upcomingBookings,
                'recent_bookings' => $recentBookings,
                'pending_payments' => $pendingPayments,
                'can_be_reviewed' => $canBeReviewed,
            ],
        ]);
    }

    /**
     * Get revenue report (Admin only)
     */
    public function revenueReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(6));
        $endDate = $request->input('end_date', Carbon::now());

        $payments = Payment::success()
                          ->whereBetween('paid_at', [$startDate, $endDate])
                          ->with('booking.package')
                          ->get();

        $totalRevenue = $payments->sum('amount');
        $totalTransactions = $payments->count();

        // Revenue by payment method
        $revenueByMethod = $payments->groupBy('payment_method')
                                   ->map(function ($group) {
                                       return [
                                           'count' => $group->count(),
                                           'total' => (float) $group->sum('amount'),
                                       ];
                                   });

        // Revenue by package
        $revenueByPackage = $payments->groupBy('booking.package.name')
                                    ->map(function ($group) {
                                        return [
                                            'count' => $group->count(),
                                            'total' => (float) $group->sum('amount'),
                                        ];
                                    });

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                'summary' => [
                    'total_revenue' => (float) $totalRevenue,
                    'total_transactions' => $totalTransactions,
                    'average_transaction' => $totalTransactions > 0 ? (float) ($totalRevenue / $totalTransactions) : 0,
                ],
                'revenue_by_payment_method' => $revenueByMethod,
                'revenue_by_package' => $revenueByPackage,
            ],
        ]);
    }
}
