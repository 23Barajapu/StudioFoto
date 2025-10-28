<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display available schedules
     */
    public function index(Request $request)
    {
        $query = Schedule::query();

        // Filter available schedules
        if ($request->has('available')) {
            $query->available();
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Filter by specific date
        if ($request->has('date')) {
            $query->where('date', $request->date);
        }

        $schedules = $query->upcoming()->get();

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    /**
     * Check availability for specific date
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $schedules = Schedule::where('date', $request->date)
                            ->available()
                            ->orderBy('start_time', 'asc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $request->date,
                'available_slots' => $schedules->count(),
                'schedules' => $schedules,
            ],
        ]);
    }

    /**
     * Store a newly created schedule (Admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for overlapping schedules
        $overlap = Schedule::where('date', $request->date)
                          ->where(function($query) use ($request) {
                              $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                    ->orWhere(function($q) use ($request) {
                                        $q->where('start_time', '<=', $request->start_time)
                                          ->where('end_time', '>=', $request->end_time);
                                    });
                          })
                          ->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal bertabrakan dengan jadwal yang sudah ada',
            ], 422);
        }

        $schedule = Schedule::create([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'available',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dibuat',
            'data' => $schedule,
        ], 201);
    }

    /**
     * Display the specified schedule
     */
    public function show($id)
    {
        $schedule = Schedule::with('booking')->find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $schedule,
        ]);
    }

    /**
     * Update the specified schedule (Admin only)
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
            ], 404);
        }

        // Cannot update booked schedules
        if ($schedule->status === 'booked') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal yang sudah dibooking tidak dapat diubah',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'status' => 'sometimes|in:available,blocked',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $schedule->fresh(),
        ]);
    }

    /**
     * Remove the specified schedule (Admin only)
     */
    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
            ], 404);
        }

        // Cannot delete booked schedules
        if ($schedule->status === 'booked') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal yang sudah dibooking tidak dapat dihapus',
            ], 422);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus',
        ]);
    }

    /**
     * Generate schedules for a date range (Admin only)
     */
    public function generateSchedules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'time_slots' => 'required|array',
            'time_slots.*.start_time' => 'required|date_format:H:i',
            'time_slots.*.end_time' => 'required|date_format:H:i|after:time_slots.*.start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $timeSlots = $request->time_slots;
        
        $created = 0;

        while ($startDate->lte($endDate)) {
            foreach ($timeSlots as $slot) {
                // Check if schedule already exists
                $exists = Schedule::where('date', $startDate->format('Y-m-d'))
                                 ->where('start_time', $slot['start_time'])
                                 ->exists();

                if (!$exists) {
                    Schedule::create([
                        'date' => $startDate->format('Y-m-d'),
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'status' => 'available',
                    ]);
                    $created++;
                }
            }

            $startDate->addDay();
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil membuat {$created} jadwal",
            'data' => [
                'created_count' => $created,
            ],
        ], 201);
    }
}
