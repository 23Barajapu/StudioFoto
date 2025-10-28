<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'booking.package']);

        // Show only approved reviews for customers
        if (!$request->user() || $request->user()->isCustomer()) {
            $query->approved();
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->rating($request->rating);
        }

        // Filter by approval status (admin only)
        if ($request->user() && $request->user()->isAdmin() && $request->has('approved')) {
            if ($request->approved === 'false' || $request->approved === '0') {
                $query->pending();
            }
        }

        $reviews = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::find($request->booking_id);

        // Check if user owns this booking
        if ($booking->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke booking ini',
            ], 403);
        }

        // Check if booking can be reviewed
        if (!$booking->canBeReviewed()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking ini tidak dapat direview',
            ], 422);
        }

        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dikirim dan menunggu persetujuan',
            'data' => $review->load(['user', 'booking']),
        ], 201);
    }

    /**
     * Display the specified review
     */
    public function show($id)
    {
        $review = Review::with(['user', 'booking.package'])->find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $review,
        ]);
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan',
            ], 404);
        }

        // Check authorization
        if ($review->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke review ini',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $review->update($request->only(['rating', 'comment']));

        // Reset approval status when updated
        if ($request->user()->isCustomer()) {
            $review->update(['is_approved' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil diperbarui',
            'data' => $review->fresh(),
        ]);
    }

    /**
     * Remove the specified review
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan',
            ], 404);
        }

        // Check authorization
        if ($review->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke review ini',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dihapus',
        ]);
    }

    /**
     * Approve review (Admin only)
     */
    public function approve($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan',
            ], 404);
        }

        $review->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil disetujui',
            'data' => $review->fresh(),
        ]);
    }

    /**
     * Reject review (Admin only)
     */
    public function reject($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditolak dan dihapus',
        ]);
    }

    /**
     * Get average rating
     */
    public function averageRating()
    {
        $average = Review::approved()->avg('rating');
        $total = Review::approved()->count();
        
        $distribution = Review::approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'average_rating' => round($average, 2),
                'total_reviews' => $total,
                'distribution' => $distribution,
            ],
        ]);
    }
}
