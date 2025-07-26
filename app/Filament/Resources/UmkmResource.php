<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\UmkmResource\Pages;
// use App\Models\Umkm;
// use Filament\Forms;
// use Filament\Resources\Resource;
// use Filament\Resources\Forms\Form;
// use Filament\Resources\Tables\Table;
// use Filament\Tables;
// use Filament\Tables\Columns\TextColumn;

// class UmkmResource extends Resource
// {
//     protected static ?string $model = Umkm::class;

//     protected static ?string $navigationIcon = 'heroicon-o-office-building';

//     protected static ?string $navigationGroup = 'Modul Komunitas & Optimasi';

//     protected static ?int $navigationSort = 1;

//     public static function form(Forms\Form $form): Forms\Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\BelongsToSelect::make('nasabah_id')
//                     ->relationship('nasabah', 'nama')
//                     ->label('Nasabah')
//                     ->required()
//                     ->searchable(),
//                 Forms\Components\TextInput::make('nama_umkm')
//                     ->label('Nama UMKM')
//                     ->required()
//                     ->maxLength(255),
//                 Forms\Components\Textarea::make('deskripsi')
//                     ->label('Deskripsi')
//                     ->required(),
//                 Forms\Components\BelongsToSelect::make('user_id')
//                     ->relationship('user', 'name')
//                     ->label('User')
//                     ->searchable()
//                     ->nullable(),
//                 Forms\Components\DateTimePicker::make('created_at')->disabled(),
//                 Forms\Components\DateTimePicker::make('updated_at')->disabled(),
//             ]);
//     }

//     public static function table(Tables\Table $table): Tables\Table
//     {
//         return $table
//             ->columns([
//                 TextColumn::make('nasabah.nama')->label('Nasabah')->sortable()->searchable(),
//                 TextColumn::make('nama_umkm')->label('Nama UMKM')->sortable()->searchable(),
//                 TextColumn::make('deskripsi')->label('Deskripsi')->limit(50)->sortable(),
//                 TextColumn::make('user.name')->label('User')->sortable()->searchable(),
//                 TextColumn::make('created_at')->dateTime()->label('Dibuat'),
//                 TextColumn::make('updated_at')->dateTime()->label('Diubah'),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//                 Tables\Actions\DeleteAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\DeleteBulkAction::make(),
//             ]);
//     }

//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListUmkm::route('/'),
//             'create' => Pages\CreateUmkm::route('/create'),
//             'edit' => Pages\EditUmkm::route('/{record}/edit'),
//         ];
//     }
// }
