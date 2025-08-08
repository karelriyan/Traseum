<?php

namespace App\Filament\Resources\RekeningResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Support\RawJs;

class SaldoTransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'saldoTransactions';

    protected static ?string $title = 'Riwayat Transaksi Saldo';

    protected static ?string $icon = 'heroicon-o-banknotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Transaksi')
                    ->schema([
                        Select::make('type')
                            ->label('Jenis Transaksi')
                            ->options([
                                'credit' => 'Kredit (Masuk)',
                                'debit' => 'Debit (Keluar)',
                            ])
                            ->required()
                            ->default('credit'),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(','),
                        Textarea::make('description')
                            ->label('Keterangan')
                            ->rows(3)
                            ->required()
                            ->placeholder('Masukkan keterangan transaksi'),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'credit',
                        'danger' => 'debit',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'credit' => 'Masuk',
                        'debit' => 'Keluar',
                        default => $state,
                    }),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->description),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'credit' => 'Kredit (Masuk)',
                        'debit' => 'Debit (Keluar)',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn($query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn($query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
