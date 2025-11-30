<?php

namespace App\Repositories;

use App\Models\Terminal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TerminalRepository extends BaseRepository
{
    public function __construct(Terminal $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active terminals
     */
    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * Get terminals with pagination and search
     */
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get terminals by type
     */
    public function getByType(string $type): Collection
    {
        return $this->model->ofType($type)->get();
    }

    /**
     * Get terminal statistics
     */
    public function getStatistics(int $terminalId, string $startDate, string $endDate): array
    {
        $terminal = $this->findOrFail($terminalId);

        return [
            'total_departures' => $terminal->departures()
                ->dateRange($startDate, $endDate)
                ->count(),
            'total_passengers' => $terminal->departures()
                ->dateRange($startDate, $endDate)
                ->sum('passengers'),
            'total_revenue' => $terminal->financialRecords()
                ->revenue()
                ->dateRange($startDate, $endDate)
                ->sum('amount'),
            'average_occupancy' => $terminal->departures()
                ->dateRange($startDate, $endDate)
                ->avg('occupancy_rate'),
        ];
    }
}
