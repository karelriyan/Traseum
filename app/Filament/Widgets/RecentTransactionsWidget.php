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
                SetorSampah::with(['rekening'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('rekening.nama_nasabah')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('total_berat')
                    ->label('Total Berat')
                    ->suffix(' kg')
                    ->numeric(2)
                    ->sortable(),
                    
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
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
