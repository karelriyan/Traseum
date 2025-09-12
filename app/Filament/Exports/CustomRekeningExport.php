<?php

namespace App\Filament\Exports;

use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Rekening;

class CustomRekeningExport extends ExcelExport
{
    public function setUp()
    {
        $this->withFilename('rekening_nasabah_custom_' . date('Y-m-d_H-i-s'));
        
        // Cek apakah ada data no_rek_pegadaian yang tidak kosong
        $hasRekPegadaian = Rekening::whereNotNull('no_rek_pegadaian')
            ->where('no_rek_pegadaian', '!=', '')
            ->exists();
            
        $columns = [
            Column::make('no_rekening')
                ->heading('No. Rekening')
                ->format(NumberFormat::FORMAT_TEXT)
                ->formatStateUsing(fn ($state) => ' ' . $state), // Space prefix untuk memaksa text
            Column::make('nama')->heading('Nama Nasabah'),
            Column::make('nik')
                ->heading('NIK')
                ->format(NumberFormat::FORMAT_TEXT)
                ->formatStateUsing(fn ($state) => ' ' . $state), // Space prefix untuk memaksa text
            Column::make('no_kk')
                ->heading('No. KK')
                ->format(NumberFormat::FORMAT_TEXT)
                ->formatStateUsing(fn ($state) => ' ' . $state), // Space prefix untuk memaksa text
            Column::make('gender')->heading('Jenis Kelamin'),
            Column::make('tanggal_lahir')
                ->heading('Tanggal Lahir')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y', strtotime($state)) : ''),
            Column::make('pendidikan')->heading('Pendidikan'),
            Column::make('dusun')->heading('Dusun'),
            Column::make('rw')->heading('RW'),
            Column::make('rt')->heading('RT'),
            Column::make('telepon')
                ->heading('No. Telepon')
                ->format(NumberFormat::FORMAT_TEXT)
                ->formatStateUsing(fn ($state) => $state ? (string) $state : ''),
            Column::make('current_balance')
                ->heading('Saldo (Rp)')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
            Column::make('points_balance')
                ->heading('Poin')
                ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0, ',', '.')),
            Column::make('status_pegadaian')
                ->heading('Tabungan Emas')
                ->formatStateUsing(fn ($state) => $state == 1 ? 'Ada' : 'Tidak Ada'),
        ];
        
        // Hanya tambahkan kolom no_rek_pegadaian jika ada data
        if ($hasRekPegadaian) {
            $columns[] = Column::make('no_rek_pegadaian')
                ->heading('No. Rek. Pegadaian')
                ->format(NumberFormat::FORMAT_TEXT)
                //->formatStateUsing(fn ($state) => $state ? (string) $state : ' '); // Space prefix untuk memaksa text
                ->formatStateUsing(fn ($state) => ' ' . $state);
        }
        
        $columns = array_merge($columns, [
            Column::make('user.name')->heading('Pembuat Rekening'),
            Column::make('created_at')
                ->heading('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : ''),
            Column::make('updated_at')
                ->heading('Terakhir Diubah')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : ''),
        ]);
        
        $this->withColumns($columns);
    }
}