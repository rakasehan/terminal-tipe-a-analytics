<?php

namespace Database\Seeders;

use App\Models\Departure;
use App\Models\Route;
use App\Models\Terminal;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DepartureSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = Terminal::all();
        $routes = Route::with('operator')->get();

        // Generate departures for last 30 days
        $startDate = now()->subDays(30);
        $endDate = now();

        $departures = [];

        foreach ($terminals as $terminal) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // 10-20 departures per day per terminal
                $departureCount = rand(10, 20);

                for ($i = 0; $i < $departureCount; $i++) {
                    $route = $routes->random();
                    $vehicle = Vehicle::where('operator_id', $route->operator_id)
                        ->where('status', 'active')
                        ->inRandomOrder()
                        ->first();

                    if (! $vehicle) {
                        continue;
                    }

                    $scheduledHour = rand(5, 22);
                    $scheduledMinute = rand(0, 59);
                    $scheduledTime = sprintf('%02d:%02d:00', $scheduledHour, $scheduledMinute);

                    $actualHour = $scheduledHour + rand(-1, 1);
                    $actualMinute = $scheduledMinute + rand(-15, 15);
                    // Clamp hour and minute to valid ranges
                    $actualHour = max(0, min(23, $actualHour));
                    $actualMinute = max(0, min(59, $actualMinute));
                    $actualTime = sprintf('%02d:%02d:00', $actualHour, $actualMinute);

                    $passengers = rand(15, $vehicle->seat_capacity);
                    $occupancyRate = ($passengers / $vehicle->seat_capacity) * 100;

                    $status = $date->lt(now()) ? 'departed' : 'scheduled';

                    $departures[] = [
                        'terminal_id' => $terminal->id,
                        'route_id' => $route->id,
                        'vehicle_id' => $vehicle->id,
                        'operator_id' => $route->operator_id,
                        'departure_date' => $date->format('Y-m-d'),
                        'scheduled_time' => $scheduledTime,
                        'actual_time' => $status === 'departed' ? $actualTime : null,
                        'passengers' => $passengers,
                        'seat_capacity' => $vehicle->seat_capacity,
                        'occupancy_rate' => round($occupancyRate, 2),
                        'revenue' => $passengers * $route->base_fare,
                        'gate_number' => 'Gate '.rand(1, $terminal->boarding_gates),
                        'status' => $status,
                        'notes' => null,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($departures, 500) as $chunk) {
            Departure::insert($chunk);
        }
    }
}
