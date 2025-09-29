<?php

namespace App\Filament\Resources\SampahResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SampahTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('berat')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('berat')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Tanggal Transaksi'),
                Tables\Columns\TextColumn::make('rekening.nama'),
                Tables\Columns\TextColumn::make('berat'),
                Tables\Columns\TextColumn::make('description'),
            ])

            ->filters([
                //
            ])
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
