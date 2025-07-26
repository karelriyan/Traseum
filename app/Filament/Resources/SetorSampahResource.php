<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\SetorSampahResource\Pages;
// use App\Models\SetorSampah;
// use Filament\Forms;
// use Filament\Resources\Resource;
// use Filament\Resources\Forms\Form;
// use Filament\Resources\Tables\Table;
// use Filament\Tables;
// use Filament\Tables\Columns\TextColumn;

// class SetorSampahResource extends Resource
// {
//     protected static ?string $model = SetorSampah::class;

//     protected static ?string $navigationIcon = 'heroicon-o-archive';

//     protected static ?string $navigationGroup = 'Modul Operasional Bank Sampah';

//     protected static ?int $navigationSort = 2;

//     public static function form(Forms\Form $form): Forms\Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\BelongsToSelect::make('rekening_id')
//                     ->relationship('rekening', 'id')
//                     ->label('Rekening')
//                     ->required()
//                     ->searchable(),
//                 Forms\Components\TextInput::make('total_saldo_dihasilkan')
//                     ->label('Total Saldo Dihasilkan')
//                     ->numeric()
//                     ->required(),
//                 Forms\Components\TextInput::make('total_poin_dihasilkan')
//                     ->label('Total Poin Dihasilkan')
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
//                 TextColumn::make('rekening.id')->label('Rekening')->sortable()->searchable(),
//                 TextColumn::make('total_saldo_dihasilkan')->label('Total Saldo Dihasilkan')->sortable(),
//                 TextColumn::make('total_poin_dihasilkan')->label('Total Poin Dihasilkan')->sortable(),
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
//             'index' => Pages\ListSetorSampah::route('/'),
//             'create' => Pages\CreateSetorSampah::route('/create'),
//             'edit' => Pages\EditSetorSampah::route('/{record}/edit'),
//         ];
//     }
// }
