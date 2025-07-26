<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostinganUmkmResource\Pages;
use App\Models\PostinganUmkm;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PostinganUmkmResource extends Resource
{
    protected static ?string $model = PostinganUmkm::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    protected static ?string $navigationGroup = 'Modul Komunitas & Optimasi';

    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('umkm_id')
                    ->relationship('umkm', 'nama_umkm')
                    ->label('UMKM')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('judul_postingan')
                    ->label('Judul Postingan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('umkm.nama_umkm')->label('UMKM')->sortable()->searchable(),
                TextColumn::make('judul_postingan')->label('Judul Postingan')->sortable()->searchable(),
                TextColumn::make('harga')->label('Harga')->sortable(),
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
            'index' => Pages\ListPostinganUmkms::route('/'),
            'create' => Pages\CreatePostinganUmkm::route('/create'),
            'edit' => Pages\EditPostinganUmkm::route('/{record}/edit'),
        ];
    }
}
