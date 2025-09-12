<?php

namespace App\Filament\Exports;

use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class CustomRekeningExport extends ExcelExport
{
    public function setUp()
    {
        $this->withFilename('rekening_nasabah_custom_' . date('Y-m-d_H-i-s'));
        
        $this->withColumns([
            Column::make('no_rekening')->heading('No. Rekening'),
            Column::make('nama')->heading('Nama Nasabah'),
            Column::make('nik')->heading('NIK'),
            Column::make('no_kk')->heading('No. KK'),
            Column::make('gender')->heading('Jenis Kelamin'),
            Column::make('tanggal_lahir')
                ->heading('Tanggal Lahir')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y', strtotime($state)) : ''),
            Column::make('pendidikan')->heading('Pendidikan'),
            Column::make('alamat')
                ->heading('Alamat')
                ->formatStateUsing(fn ($record) => "Dusun {$record->dusun}, RW {$record->rw}, RT {$record->rt}"),
            Column::make('telepon')->heading('No. Telepon'),
            Column::make('current_balance')
                ->heading('Saldo (Rp)')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Column::make('points_balance')
                ->heading('Poin')
                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
            Column::make('status_pegadaian')
                ->heading('Tab. Emas')
                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),
            Column::make('no_rek_pegadaian')->heading('No. Rek. Pegadaian'),
            Column::make('user.name')->heading('Pembuat Rekening'),
            Column::make('created_at')
                ->heading('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : ''),
            Column::make('updated_at')
                ->heading('Terakhir Diubah')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : ''),
        ]);
    }
}