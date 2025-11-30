<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'operator_id',
        'code',
        'origin_city',
        'destination_city',
        'type',
        'distance',
        'base_fare',
        'estimated_duration',
        'stops',
        'status',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'base_fare' => 'decimal:2',
        'estimated_duration' => 'integer',
        'stops' => 'array',
    ];

    /**
     * Get the operator for this route
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Get all departures for this route
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Scope for active routes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for routes by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get route full name
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->origin_city} - {$this->destination_city}";
    }
}
