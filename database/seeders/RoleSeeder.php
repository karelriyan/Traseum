<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat roles dasar untuk bank sampah
        $roles = [
            'Super Admin',
            'Operator', 
            'Petugas'
        ];

        // Update role admin yang lowercase menjadi Admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->name = 'Admin';
            $adminRole->save();
            $this->command->info("✅ Role 'admin' diupdate menjadi 'Admin'");
        } else {
            // Jika tidak ada, cek apakah sudah ada Admin
            if (!Role::where('name', 'Admin')->exists()) {
                $role = new Role();
                $role->name = 'Admin';
                $role->guard_name = 'web';
                $role->save();
                $this->command->info("✅ Role 'Admin' berhasil dibuat");
            }
        }

        foreach ($roles as $roleName) {
            $existingRole = Role::where('name', $roleName)->first();
            if (!$existingRole) {
                $role = new Role();
                $role->name = $roleName;
                $role->guard_name = 'web';
                $role->save();
                $this->command->info("✅ Role '{$roleName}' berhasil dibuat");
            } else {
                $this->command->info("ℹ️ Role '{$roleName}' sudah ada");
            }
        }

        $this->command->info('✅ Semua roles berhasil diproses');
    }
}
