<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relasi ke Booking
     */
    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    /**
     * Scope untuk filter available schedules
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                    ->where('date', '>=', Carbon::today());
    }

    /**
     * Scope untuk filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter upcoming schedules
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today())
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc');
    }

    /**
     * Check if schedule is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && 
               $this->date >= Carbon::today();
    }

    /**
     * Check if schedule is in the past
     */
    public function isPast(): bool
    {
        return $this->date < Carbon::today();
    }

    /**
     * Get formatted date time
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return Carbon::parse($this->date)->format('d/m/Y') . ' ' . 
               Carbon::parse($this->start_time)->format('H:i') . ' - ' . 
               Carbon::parse($this->end_time)->format('H:i');
    }
}
