<?php
/**
 * Script untuk troubleshooting akses superadmin
 * Jalankan di terminal: php artisan tinker
 * Atau buat route sementara untuk debugging
 */

// 1. Cek apakah user superadmin ada
$user = \App\Models\User::where('email', 'superadmin@ciptamuri.com')->first();
if (!$user) {
    echo "❌ User superadmin tidak ditemukan!\n";
    echo "Jalankan: php artisan db:seed --class=UserSeeder\n";
    exit;
}

echo "✅ User superadmin ditemukan: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "ID: " . $user->id . "\n";

// 2. Cek roles yang ada
$roles = \Spatie\Permission\Models\Role::all();
echo "\n📋 Roles yang ada:\n";
foreach ($roles as $role) {
    echo "- {$role->name} (guard: {$role->guard_name})\n";
}

// 3. Cek roles user superadmin
$userRoles = $user->roles;
echo "\n👤 Roles user superadmin:\n";
if ($userRoles->isEmpty()) {
    echo "❌ User tidak memiliki role!\n";
} else {
    foreach ($userRoles as $role) {
        echo "- {$role->name}\n";
    }
}

// 4. Test method isSuperAdmin
echo "\n🔍 Test isSuperAdmin(): ";
echo $user->isSuperAdmin() ? "✅ TRUE" : "❌ FALSE";
echo "\n";

// 5. Test hasRole
echo "🔍 Test hasRole('Super Admin'): ";
echo $user->hasRole('Super Admin') ? "✅ TRUE" : "❌ FALSE";
echo "\n";

// 6. Cek apakah ada masalah dengan guard
echo "\n🔒 Guard checking:\n";
echo "Default guard: " . config('auth.defaults.guard') . "\n";
echo "Spatie guard: " . config('permission.defaults.guard') . "\n";

// 7. Cek permission tables
echo "\n🗃️ Database tables check:\n";
echo "Users count: " . \App\Models\User::count() . "\n";
echo "Roles count: " . \Spatie\Permission\Models\Role::count() . "\n";
echo "Permissions count: " . \Spatie\Permission\Models\Permission::count() . "\n";

try {
    $modelHasRoles = \DB::table('model_has_roles')->where('model_id', $user->id)->count();
    echo "model_has_roles for user: " . $modelHasRoles . "\n";
} catch (\Exception $e) {
    echo "❌ Error accessing model_has_roles: " . $e->getMessage() . "\n";
}

// 8. Manual role assignment (jika diperlukan)
echo "\n🔧 Manual fix (uncomment if needed):\n";
echo "// \$superRole = \\Spatie\\Permission\\Models\\Role::where('name', 'Super Admin')->first();\n";
echo "// \$user->assignRole(\$superRole);\n";
echo "// echo \"Role assigned!\";\n";
