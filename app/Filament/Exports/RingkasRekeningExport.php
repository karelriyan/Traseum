<?php

namespace App\Filament\Exports;

use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class RingkasRekeningExport extends ExcelExport
{
    public function setUp()
    {
        $this->withFilename('rekening_nasabah_ringkas_' . date('Y-m-d_H-i-s'));
        
        $this->withColumns([
            Column::make('no_rekening')->heading('No. Rekening'),
            Column::make('nama')->heading('Nama Nasabah'),
            Column::make('nik')->heading('NIK'),
            Column::make('current_balance')
                ->heading('Saldo (Rp)')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Column::make('points_balance')
                ->heading('Poin')
                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
        ]);
    }
}