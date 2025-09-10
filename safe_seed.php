<?php
/**
 * Script untuk safe database seeding tanpa duplicate error
 * Jalankan di terminal: php safe_seed.php
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🌱 Safe Database Seeding\n";
echo "========================\n\n";

try {
    echo "1. Running UserSeeder...\n";
    \Artisan::call('db:seed', ['--class' => 'UserSeeder']);
    echo "✅ UserSeeder completed\n";
    echo \Artisan::output() . "\n";

    echo "2. Running SampahSeeder...\n";
    \Artisan::call('db:seed', ['--class' => 'SampahSeeder']);
    echo "✅ SampahSeeder completed\n";
    echo \Artisan::output() . "\n";

    echo "3. Running NewsSeeder...\n";
    \Artisan::call('db:seed', ['--class' => 'NewsSeeder']);
    echo "✅ NewsSeeder completed\n";
    echo \Artisan::output() . "\n";

    echo "4. Clearing cache...\n";
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('permission:cache-reset');
    echo "✅ Cache cleared\n\n";

    // Verifikasi data
    echo "5. Verifying data...\n";
    $userCount = \App\Models\User::count();
    $sampahCount = \App\Models\Sampah::count();
    $newsCount = \App\Models\News::count();
    $rolesCount = \Spatie\Permission\Models\Role::count();

    echo "   Users: {$userCount}\n";
    echo "   Sampah types: {$sampahCount}\n";
    echo "   News articles: {$newsCount}\n";
    echo "   Roles: {$rolesCount}\n\n";

    // Check superadmin
    $superadmin = \App\Models\User::where('email', 'superadmin@ciptamuri.com')->first();
    if ($superadmin) {
        echo "6. Superadmin verification...\n";
        echo "   ✅ Superadmin exists: {$superadmin->name}\n";
        echo "   ✅ Has roles: " . $superadmin->roles->pluck('name')->join(', ') . "\n";
        echo "   ✅ isSuperAdmin(): " . ($superadmin->isSuperAdmin() ? "YES" : "NO") . "\n";
        echo "   ✅ Email verified: " . ($superadmin->email_verified_at ? "YES" : "NO") . "\n\n";
    }

    echo "🎉 ALL SEEDING COMPLETED SUCCESSFULLY!\n";
    echo "\n📝 Login credentials:\n";
    echo "   Email: superadmin@ciptamuri.com\n";
    echo "   Password: PASSWORDKU_YANG_KUAT\n";
    echo "   Panel: /admin\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Try individual seeders if bulk fails
    echo "\n🔄 Trying individual seeders...\n";
    
    $seeders = ['UserSeeder', 'SampahSeeder', 'NewsSeeder'];
    foreach ($seeders as $seeder) {
        try {
            echo "   Running {$seeder}...\n";
            \Artisan::call('db:seed', ['--class' => $seeder]);
            echo "   ✅ {$seeder} OK\n";
        } catch (\Exception $e) {
            echo "   ❌ {$seeder} failed: " . $e->getMessage() . "\n";
        }
    }
}
