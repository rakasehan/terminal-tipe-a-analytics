<?php

namespace Database\Seeders;

use App\Models\Terminal;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = [
            [
                'code' => 'TRM-JKT-001',
                'name' => 'Terminal Kampung Rambutan',
                'type' => 'A',
                'address' => 'Jl. Raya Bogor KM. 22, Cijantung',
                'city' => 'Jakarta Timur',
                'province' => 'DKI Jakarta',
                'latitude' => -6.3114,
                'longitude' => 106.8776,
                'capacity' => 500,
                'boarding_gates' => 50,
                'parking_slots' => 200,
                'phone' => '021-8400517',
                'email' => 'kampungrambutan@terminal.id',
                'status' => 'active',
            ],
            [
                'code' => 'TRM-BDG-001',
                'name' => 'Terminal Leuwi Panjang',
                'type' => 'A',
                'address' => 'Jl. Soekarno Hatta No. 729',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'latitude' => -6.9558,
                'longitude' => 107.6615,
                'capacity' => 400,
                'boarding_gates' => 40,
                'parking_slots' => 150,
                'phone' => '022-7563481',
                'email' => 'leuwipanjang@terminal.id',
                'status' => 'active',
            ],
            [
                'code' => 'TRM-SBY-001',
                'name' => 'Terminal Purabaya',
                'type' => 'A',
                'address' => 'Jl. Jenderal Ahmad Yani No. 1',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'latitude' => -7.3327,
                'longitude' => 112.7604,
                'capacity' => 600,
                'boarding_gates' => 60,
                'parking_slots' => 250,
                'phone' => '031-8284444',
                'email' => 'purabaya@terminal.id',
                'status' => 'active',
            ],
            [
                'code' => 'TRM-SMG-001',
                'name' => 'Terminal Terboyo',
                'type' => 'A',
                'address' => 'Jl. Kaligawe KM. 4',
                'city' => 'Semarang',
                'province' => 'Jawa Tengah',
                'latitude' => -6.9667,
                'longitude' => 110.4500,
                'capacity' => 350,
                'boarding_gates' => 35,
                'parking_slots' => 120,
                'phone' => '024-6580100',
                'email' => 'terboyo@terminal.id',
                'status' => 'active',
            ],
            [
                'code' => 'TRM-YK-001',
                'name' => 'Terminal Giwangan',
                'type' => 'A',
                'address' => 'Jl. Imogiri Timur No. 99',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'latitude' => -7.8237,
                'longitude' => 110.3910,
                'capacity' => 300,
                'boarding_gates' => 30,
                'parking_slots' => 100,
                'phone' => '0274-376639',
                'email' => 'giwangan@terminal.id',
                'status' => 'active',
            ],
        ];

        foreach ($terminals as $terminal) {
            Terminal::create($terminal);
        }
    }
}
