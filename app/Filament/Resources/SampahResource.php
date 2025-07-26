<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\SampahResource\Pages;
// use App\Models\Sampah;
// use Filament\Forms;
// use Filament\Resources\Resource;
// use Filament\Resources\Forms\Form;
// use Filament\Resources\Tables\Table;
// use Filament\Tables;
// use Filament\Tables\Columns\TextColumn;

// class SampahResource extends Resource
// {
//     protected static ?string $model = Sampah::class;

//     protected static ?string $navigationIcon = 'heroicon-o-recycle';

//     protected static ?string $navigationGroup = 'Modul Operasional Bank Sampah';

//     protected static ?int $navigationSort = 1;

//     public static function form(Forms\Form $form): Forms\Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\TextInput::make('jenis_sampah')
//                     ->label('Jenis Sampah')
//                     ->required()
//                     ->maxLength(255),
//                 Forms\Components\TextInput::make('saldo_per_kg')
//                     ->label('Saldo per Kg')
//                     ->numeric()
//                     ->required(),
//                 Forms\Components\TextInput::make('poin_per_kg')
//                     ->label('Poin per Kg')
//                     ->numeric()
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
//                 TextColumn::make('jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(),
//                 TextColumn::make('saldo_per_kg')->label('Saldo per Kg')->sortable(),
//                 TextColumn::make('poin_per_kg')->label('Poin per Kg')->sortable(),
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
//             'index' => Pages\ListSampah::route('/'),
//             'create' => Pages\CreateSampah::route('/create'),
//             'edit' => Pages\EditSampah::route('/{record}/edit'),
//         ];
//     }
// }
