<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Schedule;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'package', 'schedule', 'payment']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        $packages = Package::active()->ordered()->get();
        $schedules = Schedule::available()->upcoming()->get();

        return view('bookings.create', compact('packages', 'schedules'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'package', 'schedule', 'payment', 'review']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,paid,in_progress,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
        ];

        if ($request->has('admin_notes')) {
            $data['admin_notes'] = $request->admin_notes;
        }

        if ($request->status === 'confirmed') {
            $data['confirmed_at'] = now();
        } elseif ($request->status === 'cancelled') {
            $data['cancelled_at'] = now();
        }

        $booking->update($data);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Status booking berhasil diperbarui');
    }
}
