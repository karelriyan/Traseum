<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampahResource\Pages;
use App\Models\Sampah;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Support\RawJs;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class SampahResource extends Resource
{
    protected static ?string $model = Sampah::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';

    protected static ?string $label = 'Kategori Sampah';

    protected static ?string $navigationGroup = 'Modul Operasional Bank Sampah';

    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_sampah')
                    ->label('Nama Jenis Sampah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('saldo_per_kg')
                    ->label('Saldo per-Kg atau Liter')
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('poin_per_kg')
                    ->label('Poin per-Kg atau Liter')
                    ->postfix('Poin')
                    ->numeric()
                    ->required(),
            ])->columns(1);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(),
                TextColumn::make('saldo_per_kg')->label('Saldo per-Kg atau Liter')->sortable()->money('IDR'),
                TextColumn::make('poin_per_kg')->label('Poin per-Kg atau Liter')->sortable(),
                TextColumn::make('user.name')->label('Pembuat Data')->sortable()->searchable(),
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
            'index' => Pages\ListSampahs::route('/'),
            'create' => Pages\CreateSampah::route('/create'),
            'edit' => Pages\EditSampah::route('/{record}/edit'),
        ];
    }
}
