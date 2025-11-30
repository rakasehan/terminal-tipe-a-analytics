<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Terminal permissions
            'view terminals',
            'create terminals',
            'edit terminals',
            'delete terminals',
            
            // Departure permissions
            'view departures',
            'create departures',
            'edit departures',
            'delete departures',
            
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Report permissions
            'view reports',
            'export reports',
            
            // Financial permissions
            'view financials',
            'create financials',
            'edit financials',
            'delete financials',
            
            // Statistics permissions
            'view statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin (BPTD Admin) - has all permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Terminal Admin - limited to their terminal
        $terminalAdmin = Role::create(['name' => 'terminal_admin']);
        $terminalAdmin->givePermissionTo([
            'view departures',
            'create departures',
            'edit departures',
            'delete departures',
            'view statistics',
            'view financials',
            'create financials',
            'edit financials',
            'delete financials',
            'view reports',
            'export reports',
        ]);
    }
}