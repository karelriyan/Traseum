<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampahSummaryResource\Pages;
use App\Models\SampahSummary;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class SampahSummaryResource extends Resource
{
    protected static ?string $model = SampahSummary::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Modul Komunitas & Optimasi';

    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('sampah_id')
                    ->relationship('sampah', 'jenis_sampah')
                    ->label('Jenis Sampah')
                    ->required()
                    ->searchable(),
                Forms\Components\DatePicker::make('tanggal_summary')
                    ->label('Tanggal Summary')
                    ->required(),
                Forms\Components\TextInput::make('total_berat_masuk')
                    ->label('Total Berat Masuk')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_berat_keluar')
                    ->label('Total Berat Keluar')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('sampah.jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(),
                TextColumn::make('tanggal_summary')->date()->label('Tanggal Summary')->sortable(),
                TextColumn::make('total_berat_masuk')->label('Total Berat Masuk')->sortable(),
                TextColumn::make('total_berat_keluar')->label('Total Berat Keluar')->sortable(),
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
            'index' => Pages\ListSampahSummaries::route('/'),
            'create' => Pages\CreateSampahSummary::route('/create'),
            'edit' => Pages\EditSampahSummary::route('/{record}/edit'),
        ];
    }
}
