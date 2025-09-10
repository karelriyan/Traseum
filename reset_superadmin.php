<?php
/**
 * Script untuk reset dan membuat superadmin dengan aman
 * Jalankan di terminal: php reset_superadmin.php
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

echo "🔄 Reset Superadmin Script\n";
echo "========================\n\n";

try {
    // 1. Pastikan role Super Admin ada
    echo "1. Membuat/memverifikasi role...\n";
    $superRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    echo "✅ Roles berhasil dibuat/diverifikasi\n\n";

    // 2. Buat atau update superadmin
    echo "2. Membuat/update user superadmin...\n";
    $user = User::updateOrCreate(
        ['email' => 'superadmin@ciptamuri.com'],
        [
            'name' => 'Super Admin',
            'password' => Hash::make('PASSWORDKU_YANG_KUAT'),
            'role' => 'Super Admin',
            'email_verified_at' => now(),
        ]
    );
    echo "✅ User superadmin berhasil dibuat/diupdate\n";
    echo "Email: {$user->email}\n";
    echo "Name: {$user->name}\n\n";

    // 3. Assign roles
    echo "3. Assign roles ke user...\n";
    $user->syncRoles([$superRole, $adminRole]);
    echo "✅ Roles berhasil di-assign\n\n";

    // 4. Verifikasi
    echo "4. Verifikasi akses...\n";
    $user->refresh(); // reload dari database
    
    echo "hasRole('Super Admin'): " . ($user->hasRole('Super Admin') ? "✅ YES" : "❌ NO") . "\n";
    echo "isSuperAdmin(): " . ($user->isSuperAdmin() ? "✅ YES" : "❌ NO") . "\n";
    
    $userRoles = $user->roles->pluck('name')->toArray();
    echo "User roles: " . implode(', ', $userRoles) . "\n\n";

    echo "🎉 SUCCESS! Superadmin siap digunakan\n";
    echo "Email: superadmin@ciptamuri.com\n";
    echo "Password: PASSWORDKU_YANG_KUAT\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
