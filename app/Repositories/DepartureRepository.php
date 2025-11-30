<?php

namespace App\Repositories;

use App\Models\Departure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartureRepository extends BaseRepository
{
    public function __construct(Departure $model)
    {
        parent::__construct($model);
    }

    /**
     * Get departures with relationships
     */
    public function getAllWithRelations(): Collection
    {
        return $this->model->with(['terminal', 'route', 'vehicle', 'operator'])->get();
    }

    /**
     * Get departures by terminal
     */
    public function getByTerminal(int $terminalId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = $this->model->where('terminal_id', $terminalId)
            ->with(['route', 'vehicle', 'operator']);

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        return $query->orderBy('departure_date', 'desc')
            ->orderBy('scheduled_time', 'desc')
            ->get();
    }

    /**
     * Get paginated departures with filters
     */
    public function getPaginatedWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['terminal', 'route', 'vehicle', 'operator']);

        if (isset($filters['terminal_id'])) {
            $query->where('terminal_id', $filters['terminal_id']);
        }

        if (isset($filters['route_id'])) {
            $query->where('route_id', $filters['route_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('route', function ($qr) use ($search) {
                    $qr->where('origin_city', 'like', "%$search%")
                        ->orWhere('destination_city', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%");
                })
                ->orWhereHas('operator', function ($qo) use ($search) {
                    $qo->where('name', 'like', "%$search%");
                })
                ->orWhereHas('vehicle', function ($qv) use ($search) {
                    $qv->where('plate_number', 'like', "%$search%");
                });
            });
        }

        return $query->orderBy('departure_date', 'desc')
            ->orderBy('scheduled_time', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get daily departures count
     */
    public function getDailyCount(int $terminalId, string $date): int
    {
        return $this->model->where('terminal_id', $terminalId)
            ->whereDate('departure_date', $date)
            ->count();
    }

    /**
     * Get occupancy statistics
     */
    public function getOccupancyStats(int $terminalId, string $startDate, string $endDate): array
    {
        $departures = $this->model->where('terminal_id', $terminalId)
            ->dateRange($startDate, $endDate)
            ->departed()
            ->get();

        return [
            'average_occupancy' => $departures->avg('occupancy_rate'),
            'total_passengers' => $departures->sum('passengers'),
            'total_capacity' => $departures->sum('seat_capacity'),
            'total_departures' => $departures->count(),
        ];
    }
}