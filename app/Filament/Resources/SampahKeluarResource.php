<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampahKeluarResource\Pages;
use App\Models\SampahKeluar;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class SampahKeluarResource extends Resource
{
    protected static ?string $model = SampahKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Modul Operasional Bank Sampah';

    protected static ?int $navigationSort = 4;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('sampah_id')
                    ->relationship('sampah', 'jenis_sampah')
                    ->label('Jenis Sampah')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('berat_keluar')
                    ->label('Berat Keluar (kg)')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->nullable(),
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
                TextColumn::make('sampah.jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(),
                TextColumn::make('berat_keluar')->label('Berat Keluar (kg)')->sortable(),
                TextColumn::make('tanggal_keluar')->date()->label('Tanggal Keluar')->sortable(),
                TextColumn::make('keterangan')->label('Keterangan')->sortable()->searchable(),
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
            'index' => Pages\ListSampahKeluars::route('/'),
            'create' => Pages\CreateSampahKeluar::route('/create'),
            'edit' => Pages\EditSampahKeluar::route('/{record}/edit'),
        ];
    }
}
