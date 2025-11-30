<?php

namespace App\Repositories;

use App\Models\PassengerStatistic;
use Illuminate\Database\Eloquent\Collection;

class PassengerStatisticRepository extends BaseRepository
{
    public function __construct(PassengerStatistic $model)
    {
        parent::__construct($model);
    }

    /**
     * Get statistics by terminal and date range
     */
    public function getByTerminalAndDateRange(int $terminalId, string $startDate, string $endDate): Collection
    {
        return $this->model->where('terminal_id', $terminalId)
            ->dateRange($startDate, $endDate)
            ->orderBy('date', 'asc')
            ->get();
    }

    /**
     * Get or create statistic for specific date
     */
    public function getOrCreateForDate(int $terminalId, string $date): PassengerStatistic
    {
        return $this->model->firstOrCreate(
            [
                'terminal_id' => $terminalId,
                'date' => $date,
            ],
            [
                'total_arrivals' => 0,
                'total_departures' => 0,
            ]
        );
    }

    /**
     * Update statistic
     */
    public function updateStatistic(int $terminalId, string $date, array $data): bool
    {
        $statistic = $this->getOrCreateForDate($terminalId, $date);
        return $statistic->update($data);
    }

    /**
     * Get peak hours analysis
     */
    public function getPeakHoursAnalysis(int $terminalId, string $startDate, string $endDate): array
    {
        $statistics = $this->getByTerminalAndDateRange($terminalId, $startDate, $endDate);

        $hourlyData = [];
        foreach ($statistics as $stat) {
            if ($stat->hourly_distribution) {
                foreach ($stat->hourly_distribution as $hour => $count) {
                    if (!isset($hourlyData[$hour])) {
                        $hourlyData[$hour] = 0;
                    }
                    $hourlyData[$hour] += $count;
                }
            }
        }

        return $hourlyData;
    }
}