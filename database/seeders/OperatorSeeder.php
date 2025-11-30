<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        $operators = [
            [
                'code' => 'PO-001',
                'name' => 'PO Haryanto',
                'address' => 'Jl. Ahmad Yani No. 123, Semarang',
                'phone' => '024-1234567',
                'email' => 'info@poharyanto.co.id',
                'director_name' => 'Budi Haryanto',
                'license_number' => 'IZ-AKAP-2024-001',
                'license_expiry' => '2026-12-31',
                'status' => 'active',
            ],
            [
                'code' => 'PO-002',
                'name' => 'PO Rosalia Indah',
                'address' => 'Jl. Solo-Sragen KM. 10, Solo',
                'phone' => '0271-7654321',
                'email' => 'info@rosaliaindah.co.id',
                'director_name' => 'Ahmad Rosadi',
                'license_number' => 'IZ-AKAP-2024-002',
                'license_expiry' => '2026-12-31',
                'status' => 'active',
            ],
            [
                'code' => 'PO-003',
                'name' => 'PO Pahala Kencana',
                'address' => 'Jl. Gatot Subroto No. 456, Jakarta',
                'phone' => '021-5551234',
                'email' => 'info@pahalakencana.co.id',
                'director_name' => 'Suhendra Pahala',
                'license_number' => 'IZ-AKAP-2024-003',
                'license_expiry' => '2026-12-31',
                'status' => 'active',
            ],
            [
                'code' => 'PO-004',
                'name' => 'PO Eka',
                'address' => 'Jl. Sukarno Hatta No. 789, Bandung',
                'phone' => '022-6667890',
                'email' => 'info@poeka.co.id',
                'director_name' => 'Eka Wijaya',
                'license_number' => 'IZ-AKDP-2024-001',
                'license_expiry' => '2026-12-31',
                'status' => 'active',
            ],
            [
                'code' => 'PO-005',
                'name' => 'PO Maju Lancar',
                'address' => 'Jl. Diponegoro No. 321, Surabaya',
                'phone' => '031-7778899',
                'email' => 'info@majulancar.co.id',
                'director_name' => 'Lancar Jaya',
                'license_number' => 'IZ-AKAP-2024-004',
                'license_expiry' => '2026-12-31',
                'status' => 'active',
            ],
        ];

        foreach ($operators as $operator) {
            Operator::create($operator);
        }
    }
}
