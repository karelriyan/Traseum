<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('loading');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();
        
        // Tentukan role berdasarkan NIK atau kriteria lain
        // Misalnya admin jika NIK diawali dengan '1234'
        $isAdmin = str_starts_with($user->nik, '1234');
        
        if ($isAdmin) {
            return Inertia::render('admin-dashboard', [
                'user' => [
                    'name' => $user->name,
                    'nik' => $user->nik,
                    'role' => 'admin'
                ]
            ]);
        } else {
            return Inertia::render('nasabah-dashboard', [
                'user' => [
                    'name' => $user->name,
                    'nik' => $user->nik,
                    'role' => 'nasabah'
                ],
                'saldo' => 'Rp 110.000,00', // Data dummy, nanti ambil dari database
                'points' => '10 MP'
            ]);
        }
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
