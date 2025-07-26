<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailSetorSampahResource\Pages;
use App\Models\DetailSetorSampah;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class DetailSetorSampahResource extends Resource
{
    protected static ?string $model = DetailSetorSampah::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Modul Operasional Bank Sampah';

    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('setor_sampah_id')
                    ->relationship('setorSampah', 'id')
                    ->label('Setor Sampah')
                    ->required()
                    ->searchable(),
                Forms\Components\BelongsToSelect::make('sampah_id')
                    ->relationship('sampah', 'jenis_sampah')
                    ->label('Jenis Sampah')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('berat')
                    ->label('Berat (kg)')
                    ->numeric()
                    ->required(),
                Forms\Components\BelongsToSelect::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->searchable()
                    ->nullable(),
                Forms\Components\DateTimePicker::make('created_at')->disabled(),
                Forms\Components\DateTimePicker::make('updated_at')->disabled(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('setorSampah.id')->label('Setor Sampah')->sortable()->searchable(),
                TextColumn::make('sampah.jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(),
                TextColumn::make('berat')->label('Berat (kg)')->sortable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime()->label('Dibuat'),
                TextColumn::make('updated_at')->dateTime()->label('Diubah'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDetailSetorSampahs::route('/'),
            'create' => Pages\CreateDetailSetorSampah::route('/create'),
            'edit' => Pages\EditDetailSetorSampah::route('/{record}/edit'),
        ];
    }
}
