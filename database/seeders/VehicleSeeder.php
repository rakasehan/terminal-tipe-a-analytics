<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $operators = Operator::all();
        $vehicleTypes = ['Ekonomi', 'Patas', 'Patas AC', 'Executive', 'Super Executive'];
        $brands = ['Mercedes-Benz', 'Hino', 'Scania', 'MAN', 'Volvo', 'Isuzu'];
        $conditions = ['excellent', 'good', 'fair'];

        $vehicles = [];

        foreach ($operators as $operator) {
            // Each operator has 10-15 vehicles
            $vehicleCount = rand(10, 15);
            
            for ($i = 1; $i <= $vehicleCount; $i++) {
                $type = $vehicleTypes[array_rand($vehicleTypes)];
                $seatCapacity = match($type) {
                    'Ekonomi' => rand(45, 54),
                    'Patas' => rand(40, 48),
                    'Patas AC' => rand(35, 42),
                    'Executive' => rand(27, 35),
                    'Super Executive' => rand(20, 27),
                };

                $vehicles[] = [
                    'operator_id' => $operator->id,
                    'plate_number' => $this->generatePlatNumber(),
                    'vehicle_type' => $type,
                    'brand' => $brands[array_rand($brands)],
                    'seat_capacity' => $seatCapacity,
                    'year' => rand(2018, 2024),
                    'condition' => $conditions[array_rand($conditions)],
                    'last_maintenance' => now()->subDays(rand(1, 90)),
                    'kir_expiry' => now()->addMonths(rand(1, 6)),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Vehicle::insert($vehicles);
    }

    private function generatePlatNumber(): string
    {
        $areas = ['B', 'D', 'F', 'H', 'L', 'N', 'AG', 'AD', 'AE'];
        $area = $areas[array_rand($areas)];
        $number = rand(1000, 9999);
        $letters = strtoupper(Str::random(3));
        
        return "{$area} {$number} {$letters}";
    }
}