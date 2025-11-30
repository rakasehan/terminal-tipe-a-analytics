<?php

namespace App\Services;

use App\Repositories\DepartureRepository;
use App\Repositories\PassengerStatisticRepository;
use App\Models\Departure;
use Illuminate\Support\Facades\DB;

class DepartureService
{
    public function __construct(
        protected DepartureRepository $departureRepository,
        protected PassengerStatisticRepository $passengerStatisticRepository
    ) {}

    /**
     * Create new departure
     */
    public function createDeparture(array $data): Departure
    {
        return DB::transaction(function () use ($data) {
            $departure = $this->departureRepository->create($data);

            // Update passenger statistics
            $this->updatePassengerStatistics($departure);

            return $departure;
        });
    }

    /**
     * Update departure
     */
    public function updateDeparture(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->departureRepository->findOrFail($id);
            $oldDate = $departure->departure_date;
            
            $updated = $this->departureRepository->update($id, $data);

            if ($updated) {
                $departure->refresh();
                
                // Update statistics for old date if date changed
                if ($oldDate != $departure->departure_date) {
                    $this->recalculateStatisticsForDate($departure->terminal_id, $oldDate);
                }
                
                // Update statistics for new date
                $this->updatePassengerStatistics($departure);
            }

            return $updated;
        });
    }

    /**
     * Delete departure
     */
    public function deleteDeparture(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $departure = $this->departureRepository->findOrFail($id);
            $terminalId = $departure->terminal_id;
            $date = $departure->departure_date;

            $deleted = $this->departureRepository->delete($id);

            if ($deleted) {
                // Recalculate statistics
                $this->recalculateStatisticsForDate($terminalId, $date);
            }

            return $deleted;
        });
    }

    /**
     * Update departure status to departed
     */
    public function markAsDeparted(int $id, string $actualTime): bool
    {
        return $this->departureRepository->update($id, [
            'status' => 'departed',
            'actual_time' => $actualTime,
        ]);
    }

    /**
     * Cancel departure
     */
    public function cancelDeparture(int $id, string $reason): bool
    {
        return $this->departureRepository->update($id, [
            'status' => 'cancelled',
            'notes' => $reason,
        ]);
    }

    /**
     * Update passenger statistics based on departure
     */
    protected function updatePassengerStatistics(Departure $departure): void
    {
        $this->recalculateStatisticsForDate($departure->terminal_id, $departure->departure_date);
    }

    /**
     * Recalculate statistics for specific date
     */
    protected function recalculateStatisticsForDate(int $terminalId, string $date): void
    {
        $departures = $this->departureRepository->getByTerminal($terminalId, $date, $date);

        $totalDepartures = $departures->count();
        $totalPassengers = $departures->sum('passengers');

        // Calculate hourly distribution
        $hourlyDistribution = [];
        foreach ($departures as $departure) {
            $hour = (int) date('H', strtotime($departure->scheduled_time));
            if (!isset($hourlyDistribution[$hour])) {
                $hourlyDistribution[$hour] = 0;
            }
            $hourlyDistribution[$hour] += $departure->passengers;
        }

        // Find peak hour
        $peakHour = !empty($hourlyDistribution) 
            ? array_keys($hourlyDistribution, max($hourlyDistribution))[0] 
            : null;

        // Calculate route distribution
        $routeDistribution = [];
        foreach ($departures as $departure) {
            $routeName = $departure->route->full_name;
            if (!isset($routeDistribution[$routeName])) {
                $routeDistribution[$routeName] = 0;
            }
            $routeDistribution[$routeName] += $departure->passengers;
        }

        // Update statistics
        $this->passengerStatisticRepository->updateStatistic($terminalId, $date, [
            'total_departures' => $totalDepartures,
            'total_arrivals' => $totalPassengers, // For now, same as departures
            'peak_hour_start' => $peakHour,
            'peak_hour_end' => $peakHour,
            'peak_hour_passengers' => $peakHour ? $hourlyDistribution[$peakHour] : 0,
            'hourly_distribution' => $hourlyDistribution,
            'route_distribution' => $routeDistribution,
        ]);
    }

    /**
     * Get departures with filters
     */
    public function getDeparturesWithFilters(array $filters, int $perPage = 15)
    {
        return $this->departureRepository->getPaginatedWithFilters($filters, $perPage);
    }

    /**
     * Validate departure data
     */
    public function validateDepartureCapacity(int $terminalId, string $date): bool
    {
        $terminal = app(TerminalService::class)->terminalRepository->findOrFail($terminalId);
        $currentCount = $this->departureRepository->getDailyCount($terminalId, $date);

        return $currentCount < $terminal->capacity;
    }
}