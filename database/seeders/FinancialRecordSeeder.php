<?php

namespace Database\Seeders;

use App\Models\FinancialRecord;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class FinancialRecordSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = Terminal::all();
        $startDate = now()->subDays(30);
        $endDate = now();

        $revenueCategories = ['retribution', 'parking', 'commercial'];
        $expenseCategories = ['operational', 'maintenance', 'utilities', 'salary'];

        foreach ($terminals as $terminal) {
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Generate 2-5 revenue records per day
                for ($i = 0; $i < rand(2, 5); $i++) {
                    $category = $revenueCategories[array_rand($revenueCategories)];
                    
                    $amount = match($category) {
                        'retribution' => rand(500000, 2000000),
                        'parking' => rand(300000, 800000),
                        'commercial' => rand(200000, 1000000),
                        default => rand(100000, 500000),
                    };

                    FinancialRecord::create([
                        'terminal_id' => $terminal->id,
                        'date' => $date->format('Y-m-d'),
                        'type' => 'revenue',
                        'category' => $category,
                        'description' => $this->getDescription('revenue', $category),
                        'amount' => $amount,
                        'reference_number' => 'REV-' . $date->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    ]);
                }

                // Generate 1-3 expense records per day
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $category = $expenseCategories[array_rand($expenseCategories)];
                    
                    $amount = match($category) {
                        'operational' => rand(200000, 800000),
                        'maintenance' => rand(300000, 1500000),
                        'utilities' => rand(150000, 500000),
                        'salary' => rand(500000, 2000000),
                        default => rand(100000, 500000),
                    };

                    FinancialRecord::create([
                        'terminal_id' => $terminal->id,
                        'date' => $date->format('Y-m-d'),
                        'type' => 'expense',
                        'category' => $category,
                        'description' => $this->getDescription('expense', $category),
                        'amount' => $amount,
                        'reference_number' => 'EXP-' . $date->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }
    }

    private function getDescription(string $type, string $category): string
    {
        if ($type === 'revenue') {
            return match($category) {
                'retribution' => 'Retribusi Terminal',
                'parking' => 'Parkir Kendaraan',
                'commercial' => 'Sewa Tenant',
                default => 'Pendapatan Lain-lain',
            };
        }

        return match($category) {
            'operational' => 'Biaya Operasional Harian',
            'maintenance' => 'Pemeliharaan Fasilitas',
            'utilities' => 'Listrik dan Air',
            'salary' => 'Gaji Pegawai',
            default => 'Pengeluaran Lain-lain',
        };
    }
}