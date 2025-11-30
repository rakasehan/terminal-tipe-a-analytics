<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Operator;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $operators = Operator::all();

        $routes = [
            // AKAP Routes (Antar Kota Antar Provinsi)
            [
                'operator_id' => $operators[0]->id,
                'code' => 'AKAP-JKT-SBY-001',
                'origin_city' => 'Jakarta',
                'destination_city' => 'Surabaya',
                'type' => 'AKAP',
                'distance' => 785.50,
                'base_fare' => 250000,
                'estimated_duration' => 720,
                'stops' => ['Cirebon', 'Semarang', 'Solo'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[0]->id,
                'code' => 'AKAP-JKT-BDG-001',
                'origin_city' => 'Jakarta',
                'destination_city' => 'Bandung',
                'type' => 'AKAP',
                'distance' => 180.00,
                'base_fare' => 80000,
                'estimated_duration' => 180,
                'stops' => ['Bekasi', 'Cikampek'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[1]->id,
                'code' => 'AKAP-JKT-YK-001',
                'origin_city' => 'Jakarta',
                'destination_city' => 'Yogyakarta',
                'type' => 'AKAP',
                'distance' => 560.00,
                'base_fare' => 180000,
                'estimated_duration' => 540,
                'stops' => ['Cirebon', 'Brebes', 'Purwokerto'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[1]->id,
                'code' => 'AKAP-BDG-SBY-001',
                'origin_city' => 'Bandung',
                'destination_city' => 'Surabaya',
                'type' => 'AKAP',
                'distance' => 725.00,
                'base_fare' => 220000,
                'estimated_duration' => 660,
                'stops' => ['Tasikmalaya', 'Cilacap', 'Banyuwangi'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[2]->id,
                'code' => 'AKAP-JKT-SMG-001',
                'origin_city' => 'Jakarta',
                'destination_city' => 'Semarang',
                'type' => 'AKAP',
                'distance' => 445.00,
                'base_fare' => 150000,
                'estimated_duration' => 420,
                'stops' => ['Cirebon', 'Brebes', 'Tegal'],
                'status' => 'active',
            ],
            
            // AKDP Routes (Antar Kota Dalam Provinsi)
            [
                'operator_id' => $operators[3]->id,
                'code' => 'AKDP-BDG-TSM-001',
                'origin_city' => 'Bandung',
                'destination_city' => 'Tasikmalaya',
                'type' => 'AKDP',
                'distance' => 125.00,
                'base_fare' => 50000,
                'estimated_duration' => 150,
                'stops' => ['Nagreg', 'Garut'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[3]->id,
                'code' => 'AKDP-BDG-CRB-001',
                'origin_city' => 'Bandung',
                'destination_city' => 'Cirebon',
                'type' => 'AKDP',
                'distance' => 145.00,
                'base_fare' => 60000,
                'estimated_duration' => 180,
                'stops' => ['Sumedang', 'Majalengka'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[4]->id,
                'code' => 'AKDP-SBY-MLG-001',
                'origin_city' => 'Surabaya',
                'destination_city' => 'Malang',
                'type' => 'AKDP',
                'distance' => 95.00,
                'base_fare' => 40000,
                'estimated_duration' => 120,
                'stops' => ['Sidoarjo', 'Pasuruan'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[4]->id,
                'code' => 'AKDP-SBY-JMB-001',
                'origin_city' => 'Surabaya',
                'destination_city' => 'Jember',
                'type' => 'AKDP',
                'distance' => 205.00,
                'base_fare' => 85000,
                'estimated_duration' => 240,
                'stops' => ['Probolinggo', 'Lumajang'],
                'status' => 'active',
            ],
            [
                'operator_id' => $operators[2]->id,
                'code' => 'AKDP-SMG-SOLO-001',
                'origin_city' => 'Semarang',
                'destination_city' => 'Solo',
                'type' => 'AKDP',
                'distance' => 65.00,
                'base_fare' => 30000,
                'estimated_duration' => 90,
                'stops' => ['Ungaran', 'Salatiga'],
                'status' => 'active',
            ],
        ];

        foreach ($routes as $route) {
            Route::create($route);
        }
    }
}