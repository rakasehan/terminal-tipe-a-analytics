<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassengerStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'terminal_id',
        'date',
        'total_arrivals',
        'total_departures',
        'peak_hour_start',
        'peak_hour_end',
        'peak_hour_passengers',
        'hourly_distribution',
        'route_distribution',
        'average_waiting_time',
    ];

    protected $casts = [
        'date' => 'date',
        'total_arrivals' => 'integer',
        'total_departures' => 'integer',
        'peak_hour_start' => 'integer',
        'peak_hour_end' => 'integer',
        'peak_hour_passengers' => 'integer',
        'hourly_distribution' => 'array',
        'route_distribution' => 'array',
        'average_waiting_time' => 'decimal:2',
    ];

    /**
     * Get the terminal for this statistic
     */
    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
