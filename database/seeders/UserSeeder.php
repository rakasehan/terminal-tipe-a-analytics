<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Terminal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = Terminal::all();
        
        // Ensure roles exist
        \Spatie\Permission\Models\Role::findOrCreate('super_admin', 'web');
        \Spatie\Permission\Models\Role::findOrCreate('terminal_admin', 'web');

        // Create Super Admin (BPTD Admin)
        $superAdmin = User::create([
            'name' => 'Admin BPTD',
            'email' => 'admin@bptd.go.id',
            'password' => Hash::make('password'),
            'terminal_id' => null,
            'phone' => '021-12345678',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        // Create Terminal Admins for each terminal
        foreach ($terminals as $index => $terminal) {
            $terminalAdmin = User::create([
                'name' => "Admin {$terminal->name}",
                'email' => strtolower(str_replace(' ', '', $terminal->code)) . '@terminal.id',
                'password' => Hash::make('password'),
                'terminal_id' => $terminal->id,
                'phone' => $terminal->phone,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $terminalAdmin->assignRole('terminal_admin');
        }

        // Create additional users for testing
        $additionalUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@terminal.id',
                'terminal_id' => $terminals[0]->id,
                'phone' => '081234567890',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@terminal.id',
                'terminal_id' => $terminals[1]->id,
                'phone' => '081234567891',
            ],
        ];

        foreach ($additionalUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'terminal_id' => $userData['terminal_id'],
                'phone' => $userData['phone'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('terminal_admin');
        }
    }
}