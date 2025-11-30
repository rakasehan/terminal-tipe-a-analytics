<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terminal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'city',
        'province',
        'latitude',
        'longitude',
        'capacity',
        'boarding_gates',
        'parking_slots',
        'phone',
        'email',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
        'boarding_gates' => 'integer',
        'parking_slots' => 'integer',
    ];

    /**
     * Get all departures for this terminal
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Get all users assigned to this terminal
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get passenger statistics for this terminal
     */
    public function passengerStatistics(): HasMany
    {
        return $this->hasMany(PassengerStatistic::class);
    }

    /**
     * Get financial records for this terminal
     */
    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }

    /**
     * Scope for active terminals
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for terminals by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
