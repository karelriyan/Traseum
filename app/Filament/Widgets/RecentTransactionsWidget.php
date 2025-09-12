<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\SetorSampah;
use Filament\Tables\Columns\TextColumn;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                SetorSampah::with(['rekening', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('rekening.nama')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->rekening?->no_rekening === '00000000000000') {
                            return 'Donasi';
                        }
                        return $state;
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('berat')
                    ->label('Total Berat')
                    ->suffix(' kg')
                    ->numeric(2)
                    ->sortable(),
                    
                TextColumn::make('total_saldo_dihasilkan')
                    ->label('Total Saldo')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('jenis_setoran')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rekening' => 'success',
                        'donasi' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rekening' => 'Rekening',
                        'donasi' => 'Donasi',
                        default => ucfirst($state),
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (SetorSampah $record): string => route('filament.admin.resources.setor-sampahs.view', $record)),
            ])
            ->heading('Transaksi Terbaru')
            ->description('10 transaksi setor sampah terbaru')
            ->emptyStateHeading('Belum ada transaksi')
            ->emptyStateDescription('Transaksi setor sampah akan muncul di sini.')
            ->paginated(false);
    }
}
