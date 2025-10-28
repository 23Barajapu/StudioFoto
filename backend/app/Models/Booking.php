<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_code',
        'user_id',
        'package_id',
        'schedule_id',
        'booking_date',
        'booking_time',
        'total_price',
        'status',
        'customer_notes',
        'admin_notes',
        'confirmed_at',
        'paid_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BKG-' . strtoupper(uniqid());
            }
        });
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relasi ke Schedule
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Relasi ke Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Relasi ke Review
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Scope untuk filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', Carbon::today())
                    ->whereIn('status', ['confirmed', 'paid'])
                    ->orderBy('booking_date', 'asc');
    }

    /**
     * Scope untuk filter pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
               $this->booking_date > Carbon::today();
    }

    /**
     * Check if booking can be reviewed
     */
    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && !$this->review;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'paid' => 'Dibayar',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }
}
