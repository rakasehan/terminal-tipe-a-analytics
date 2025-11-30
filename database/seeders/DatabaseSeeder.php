<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            TerminalSeeder::class,
            OperatorSeeder::class,
            RouteSeeder::class,
            VehicleSeeder::class,
            UserSeeder::class,
            DepartureSeeder::class,
            PassengerStatisticSeeder::class,
            FinancialRecordSeeder::class,
        ]);
    }
}