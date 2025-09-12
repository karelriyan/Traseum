<?php

namespace App\Filament\Resources\SampahResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class DetailSetorSampahRelationManager extends RelationManager
{
    protected static string $relationship = 'detailSetorSampahs';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Riwayat Setor Sampah';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only, as details are created via SetorSampahResource
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('setorSampah.rekening.nama')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, Model $record) {
                        if ($record->setorSampah->rekening?->no_rekening === '00000000000000') {
                            return 'Donasi';
                        }
                        return $state;
                    })
                    ->badge(fn(Model $record) => $record->setorSampah->rekening?->no_rekening === '00000000000000')
                    ->color(fn(Model $record) => $record->setorSampah->rekening?->no_rekening === '00000000000000' ? 'success' : null),
                TextColumn::make('berat')
                    ->label('Berat (Kg)')
                    ->numeric(decimalPlaces: 4)
                    ->sortable(),
                TextColumn::make('setorSampah.user.name')
                    ->label('Petugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Setor')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function canCreate(): bool
    {
        return false;
    }

    public function canEdit(Model $record): bool
    {
        return false;
    }

    public function canDelete(Model $record): bool
    {
        return false;
    }
}