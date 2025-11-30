<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Departure extends Model
{
    use HasFactory;

    protected $fillable = [
        'terminal_id',
        'route_id',
        'vehicle_id',
        'operator_id',
        'departure_date',
        'scheduled_time',
        'actual_time',
        'passengers',
        'seat_capacity',
        'occupancy_rate',
        'revenue',
        'gate_number',
        'status',
        'notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'passengers' => 'integer',
        'seat_capacity' => 'integer',
        'occupancy_rate' => 'decimal:2',
        'revenue' => 'decimal:2',
    ];

    /**
     * Get the terminal for this departure
     */
    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    /**
     * Get the route for this departure
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the vehicle for this departure
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the operator for this departure
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate occupancy rate before saving
        static::saving(function ($departure) {
            if ($departure->passengers && $departure->seat_capacity) {
                $departure->occupancy_rate = ($departure->passengers / $departure->seat_capacity) * 100;
            }
        });
    }

    /**
     * Scope for departures by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('departure_date', [$startDate, $endDate]);
    }

    /**
     * Scope for departed status
     */
    public function scopeDeparted($query)
    {
        return $query->where('status', 'departed');
    }
}