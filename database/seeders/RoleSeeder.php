<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Hexters\HexaLite\Models\HexaRole;

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
        $adminRole = HexaRole::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->name = 'Admin';
            $adminRole->guard = hexa()->guard();
            $adminRole->save();
            $this->command->info("✅ Role 'admin' diupdate menjadi 'Admin'");
        } else {
            // Jika tidak ada, cek apakah sudah ada Admin
            if (!HexaRole::where('name', 'Admin')->where('guard', hexa()->guard())->exists()) {
                $role = new HexaRole();
                $role->name = 'Admin';
                $role->guard = hexa()->guard();
                $role->save();
                $this->command->info("✅ Role 'Admin' berhasil dibuat");
            }
        }

        foreach ($roles as $roleName) {
            $existingRole = HexaRole::where('name', $roleName)->where('guard', hexa()->guard())->first();
            if (!$existingRole) {
                $role = new HexaRole();
                $role->name = $roleName;
                $role->guard = hexa()->guard();
                $role->save();
                $this->command->info("✅ Role '{$roleName}' berhasil dibuat");
            } else {
                $this->command->info("ℹ️ Role '{$roleName}' sudah ada");
            }
        }

        $this->command->info('✅ Semua roles berhasil diproses');
    }
}
