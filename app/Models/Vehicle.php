<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'operator_id',
        'plate_number',
        'vehicle_type',
        'brand',
        'seat_capacity',
        'year',
        'condition',
        'last_maintenance',
        'kir_expiry',
        'status',
    ];

    protected $casts = [
        'seat_capacity' => 'integer',
        'year' => 'integer',
        'last_maintenance' => 'date',
        'kir_expiry' => 'date',
    ];

    /**
     * Get the operator for this vehicle
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Get all departures for this vehicle
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Scope for active vehicles
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if KIR is expired
     */
    public function getIsKirExpiredAttribute(): bool
    {
        return $this->kir_expiry && $this->kir_expiry->isPast();
    }
}
