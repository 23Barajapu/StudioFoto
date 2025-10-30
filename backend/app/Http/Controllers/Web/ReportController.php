<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display revenue report
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfDay()->format('Y-m-d'));
        $groupBy = $request->input('group_by', 'day'); // day, month, year
        $status = $request->input('status', 'completed');

        // Validate date range
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'group_by' => 'in:day,month,year',
            'status' => 'in:all,completed,pending,confirmed,cancelled'
        ]);

        $query = Booking::with(['package', 'user'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Group by selected period
        $dateFormat = $this->getDateFormat($groupBy);
        
        $revenues = $query->select(
                DB::raw("DATE_FORMAT(booking_date, '{$dateFormat}') as period"),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(*) as total_bookings')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Get summary
        $summary = [
            'total_revenue' => $revenues->sum('total_revenue'),
            'total_bookings' => $revenues->sum('total_bookings'),
            'average_revenue' => $revenues->avg('total_revenue'),
        ];

        // Get top packages
        $topPackages = Booking::select(
                'package_id',
                'packages.name as package_name',
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->groupBy('package_id', 'packages.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('reports.revenue', compact(
            'revenues',
            'summary',
            'topPackages',
            'startDate',
            'endDate',
            'groupBy',
            'status'
        ));
    }

    /**
     * Get date format for grouping
     */
    private function getDateFormat($groupBy)
    {
        return match($groupBy) {
            'day' => '%Y-%m-%d',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d',
        };
    }
}
