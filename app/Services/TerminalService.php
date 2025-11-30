<?php

namespace App\Services;

use App\Repositories\TerminalRepository;
use App\Repositories\DepartureRepository;
use App\Repositories\PassengerStatisticRepository;
use App\Repositories\FinancialRecordRepository;
use Illuminate\Support\Facades\DB;

class TerminalService
{
    public function __construct(
        protected TerminalRepository $terminalRepository,
        protected DepartureRepository $departureRepository,
        protected PassengerStatisticRepository $passengerStatisticRepository,
        protected FinancialRecordRepository $financialRecordRepository
    ) {}

    /**
     * Get terminal dashboard data
     */
    public function getDashboardData(int $terminalId, ?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $endDate ?? now()->format('Y-m-d');

        $terminal = $this->terminalRepository->findOrFail($terminalId);

        // Get basic statistics
        $statistics = $this->terminalRepository->getStatistics($terminalId, $startDate, $endDate);

        // Get occupancy stats
        $occupancyStats = $this->departureRepository->getOccupancyStats($terminalId, $startDate, $endDate);

        // Get financial summary
        $financialSummary = $this->financialRecordRepository->getFinancialSummary($terminalId, $startDate, $endDate);

        // Get recent departures
        $recentDepartures = $this->departureRepository->getByTerminal($terminalId, $startDate, $endDate)
            ->take(10);

        // Get passenger statistics
        $passengerStats = $this->passengerStatisticRepository->getByTerminalAndDateRange($terminalId, $startDate, $endDate);

        return [
            'terminal' => $terminal,
            'statistics' => $statistics,
            'occupancy_stats' => $occupancyStats,
            'financial_summary' => $financialSummary,
            'recent_departures' => $recentDepartures,
            'passenger_stats' => $passengerStats,
            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];
    }

    /**
     * Get analytics data for charts
     */
    public function getAnalyticsData(int $terminalId, string $startDate, string $endDate): array
    {
        // Daily passengers trend
        $passengerStats = $this->passengerStatisticRepository->getByTerminalAndDateRange($terminalId, $startDate, $endDate);
        
        $dailyTrend = $passengerStats->map(function ($stat) {
            return [
                'date' => $stat->date->format('Y-m-d'),
                'arrivals' => $stat->total_arrivals,
                'departures' => $stat->total_departures,
            ];
        });

        // Peak hours analysis
        $peakHours = $this->passengerStatisticRepository->getPeakHoursAnalysis($terminalId, $startDate, $endDate);

        // Route distribution
        $routeDistribution = $passengerStats->pluck('route_distribution')
            ->filter()
            ->flatMap(fn($routes) => $routes)
            ->groupBy(fn($value, $key) => $key)
            ->map(fn($items) => $items->sum());

        // Financial monthly trend
        $year = now()->year;
        $financialTrend = $this->financialRecordRepository->getMonthlyTrend($terminalId, $year);

        return [
            'daily_trend' => $dailyTrend,
            'peak_hours' => $peakHours,
            'route_distribution' => $routeDistribution,
            'financial_trend' => $financialTrend,
        ];
    }

    /**
     * Create new terminal
     */
    public function createTerminal(array $data): \App\Models\Terminal
    {
        return DB::transaction(function () use ($data) {
            return $this->terminalRepository->create($data);
        });
    }

    /**
     * Update terminal
     */
    public function updateTerminal(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->terminalRepository->update($id, $data);
        });
    }

    /**
     * Delete terminal
     */
    public function deleteTerminal(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            return $this->terminalRepository->delete($id);
        });
    }

    /**
     * Get all terminals for dropdown
     */
    public function getTerminalsForDropdown(): array
    {
        return $this->terminalRepository->getActive()
            ->pluck('name', 'id')
            ->toArray();
    }
}