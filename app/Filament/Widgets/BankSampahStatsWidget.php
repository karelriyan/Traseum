<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Rekening;
use App\Models\User;
use App\Models\SetorSampah;
use App\Models\SaldoTransaction;

class BankSampahStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Nasabah', Rekening::count())
                ->description('Jumlah rekening nasabah aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Total Transaksi Hari Ini', SetorSampah::whereDate('created_at', today())->count())
                ->description('Transaksi sampah hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
                
            Stat::make('Total Saldo', 'Rp ' . number_format(SaldoTransaction::where('type', 'credit')->sum('amount') - SaldoTransaction::where('type', 'debit')->sum('amount'), 0, ',', '.'))
                ->description('Total saldo semua nasabah')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
                
            Stat::make('Admin Aktif', User::whereHas('roles')->count())
                ->description('Jumlah admin dan petugas')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('primary'),
        ];
    }
}
