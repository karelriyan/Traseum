<?php

namespace App\Filament\Resources\RekeningResource\Widgets;

use App\Models\Rekening;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class RekeningOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = true;

    protected function getFilters(): array
    {
        return [
            DatePicker::make('startDate')
                ->label('Tanggal Mulai')
                ->default(now()->startOfMonth()),
            DatePicker::make('endDate')
                ->label('Tanggal Selesai')
                ->default(now()->endOfMonth()),
        ];
    }

    protected function getStats(): array
    {
        $startDate = Carbon::parse($this->filters['startDate'] ?? now()->startOfMonth());
        $endDate = Carbon::parse($this->filters['endDate'] ?? now()->endOfMonth());

        // Stat 1: Jumlah rekening baru dalam periode filter
        $jumlahRekeningBaru = Rekening::where('no_rekening', '!=', '00000000')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Stat 2: Total saldo nasabah tersimpan (nilai saat ini, tidak terpengaruh filter)
        $saldoNasabah = Rekening::where('no_rekening', '!=', '00000000')->sum('balance');

        // Stat 3: Jumlah tabungan sampah / kas (nilai saat ini, tidak terpengaruh filter)
        $kasBankSampah = Rekening::where('no_rekening', '00000000')->value('balance') ?? 0;

        // --- STATISTIK BARU ---
        // Stat 4: Jumlah nasabah dengan tabungan emas pegadaian (tidak terpengaruh filter)
        $nasabahPegadaian = Rekening::where('status_pegadaian', true)->count();

        return [
            Stat::make('Rekening Baru Dibuat', $jumlahRekeningBaru)
                ->description('Nasabah baru dalam periode filter')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),
            // Stat::make('Total Kas Bank Sampah', 'Rp ' . Number::format($kasBankSampah, locale: 'id'))
            //     ->description('Snapshot total kas donasi saat ini')
            //     ->descriptionIcon('heroicon-m-building-office')
            //     ->color('info'),
            // --- KARTU STATISTIK BARU ---
            Stat::make('Nasabah Pegadaian', $nasabahPegadaian)
                ->description('Total nasabah pemilik Tabungan Emas')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning'),
            Stat::make('Total Saldo Nasabah', 'Rp ' . Number::format($saldoNasabah, locale: 'id'))
                ->description('Snapshot total saldo tersimpan saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('primary'),
        ];
    }
}

