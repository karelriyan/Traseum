<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoinTransactionResource\Pages;
use App\Models\PoinTransaction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PoinTransactionResource extends Resource
{
    protected static ?string $model = PoinTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Manajemen Pengguna & Keuangan';

    protected static ?int $navigationSort = 5;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('rekening_id')
                    ->relationship('rekening', 'id')
                    ->label('Rekening')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('transactable_type')
                    ->label('Tipe Transaksi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('transactable_id')
                    ->label('ID Transaksi')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi')
                    ->maxLength(255),
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
                TextColumn::make('rekening.id')->label('Rekening')->sortable()->searchable(),
                TextColumn::make('amount')->label('Jumlah')->sortable(),
                TextColumn::make('transactable_type')->label('Tipe Transaksi')->sortable()->searchable(),
                TextColumn::make('transactable_id')->label('ID Transaksi')->sortable(),
                TextColumn::make('description')->label('Deskripsi')->sortable()->searchable(),
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
            'index' => Pages\ListPoinTransactions::route('/'),
            'create' => Pages\CreatePoinTransaction::route('/create'),
            'edit' => Pages\EditPoinTransaction::route('/{record}/edit'),
        ];
    }
}
