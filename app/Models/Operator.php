<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'director_name',
        'license_number',
        'license_expiry',
        'status',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    /**
     * Get all routes for this operator
     */
    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    /**
     * Get all vehicles for this operator
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get all departures for this operator
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Scope for active operators
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}