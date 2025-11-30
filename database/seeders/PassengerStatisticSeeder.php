<?php

namespace Database\Seeders;

use App\Models\PassengerStatistic;
use App\Models\Terminal;
use App\Models\Departure;
use Illuminate\Database\Seeder;

class PassengerStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = Terminal::all();
        $startDate = now()->subDays(30);
        $endDate = now();

        foreach ($terminals as $terminal) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $departures = Departure::where('terminal_id', $terminal->id)
                    ->whereDate('departure_date', $date->format('Y-m-d'))
                    ->get();

                if ($departures->isEmpty()) continue;

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

                PassengerStatistic::create([
                    'terminal_id' => $terminal->id,
                    'date' => $date->format('Y-m-d'),
                    'total_arrivals' => $totalPassengers,
                    'total_departures' => $totalDepartures,
                    'peak_hour_start' => $peakHour,
                    'peak_hour_end' => $peakHour,
                    'peak_hour_passengers' => $peakHour ? $hourlyDistribution[$peakHour] : 0,
                    'hourly_distribution' => $hourlyDistribution,
                    'route_distribution' => $routeDistribution,
                    'average_waiting_time' => rand(10, 45),
                ]);
            }
        }
    }
}