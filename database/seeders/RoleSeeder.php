<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Member permissions
            'view members',
            'create members',
            'edit members',
            'delete members',
            
            // Waste Type permissions
            'view waste-types',
            'create waste-types',
            'edit waste-types',
            'delete waste-types',
            
            // Deposit permissions
            'view deposits',
            'create deposits',
            'edit deposits',
            'delete deposits',
            
            // Withdrawal permissions
            'view withdrawals',
            'create withdrawals',
            'edit withdrawals',
            'delete withdrawals',
            'approve withdrawals',
            'reject withdrawals',
            
            // Partner permissions
            'view partners',
            'create partners',
            'edit partners',
            'delete partners',
            
            // Dashboard permissions
            'view dashboard',
            'view stats',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - Full access
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Petugas role - Limited operational access
        $petugasRole = Role::create(['name' => 'petugas']);
        $petugasRole->givePermissionTo([
            'view members',
            'view waste-types',
            'view deposits',
            'create deposits',
            'edit deposits',
            'view withdrawals',
            'create withdrawals',
            'view partners',
            'view dashboard',
            'view stats',
        ]);

        // Viewer role - Read-only access
        $viewerRole = Role::create(['name' => 'viewer']);
        $viewerRole->givePermissionTo([
            'view members',
            'view waste-types',
            'view deposits',
            'view withdrawals',
            'view partners',
            'view dashboard',
            'view stats',
            'view reports',
        ]);
    }
}
