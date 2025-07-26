<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetorSampahResource\Pages;
use App\Models\SetorSampah;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Forms\Form;
use Filament\Resources\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model; // Import Model

class SetorSampahResource extends Resource
{
    protected static ?string $model = SetorSampah::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Modul Operasional Bank Sampah';

    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Informasi Sampah Masuk')
                    ->schema([
                        Forms\Components\Select::make('rekening_id') // Changed to Select for better UX
                            ->relationship('rekening', 'id')
                            ->label('Rekening')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('sampah_id') // Changed to Select
                            ->relationship('sampah', 'jenis_sampah') // Display jenis_sampah
                            ->label('Jenis Sampah')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->reactive() // Make it reactive
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $sampah = \App\Models\Sampah::find($state);
                                $berat = (float) $get('berat'); // Get current berat value
                    
                                if ($sampah && $berat > 0) {
                                    // Convert gram/mL to kg/liter (divide by 1000)
                                    $berat_kg_liter = $berat / 1000;
                                    $total_saldo = $sampah->saldo_per_kg * $berat_kg_liter;
                                    $total_poin = $sampah->poin_per_kg * $berat_kg_liter;

                                    $set('total_saldo_dihasilkan', $total_saldo);
                                    $set('total_poin_dihasilkan', $total_poin);
                                } else {
                                    $set('total_saldo_dihasilkan', 0);
                                    $set('total_poin_dihasilkan', 0);
                                }
                            }),
                        Forms\Components\TextInput::make('berat')
                            ->label('Berat')
                            ->postfix('gram atau mL')
                            ->numeric()
                            ->required()
                            ->reactive() // Make it reactive
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $sampah_id = $get('sampah_id');
                                $sampah = \App\Models\Sampah::find($sampah_id);
                                $berat = (float) $state;

                                if ($sampah && $berat > 0) {
                                    // Convert gram/mL to kg/liter (divide by 1000)
                                    $berat_kg_liter = $berat / 1000;
                                    $total_saldo = $sampah->saldo_per_kg * $berat_kg_liter;
                                    $total_poin = $sampah->poin_per_kg * $berat_kg_liter;

                                    $set('total_saldo_dihasilkan', $total_saldo);
                                    $set('total_poin_dihasilkan', $total_poin);
                                } else {
                                    $set('total_saldo_dihasilkan', 0);
                                    $set('total_poin_dihasilkan', 0);
                                }
                            }),
                    ]),

                Section::make('Perhitungan Otomatis')
                    ->Schema([
                        Forms\Components\TextInput::make('total_saldo_dihasilkan')
                            ->label('Total Saldo Dihasilkan')
                            ->numeric()
                            ->hidden() // Make it hidden
                            ->dehydrated(true), // Ensure it's saved to DB

                        Forms\Components\Placeholder::make('total_saldo_dihasilkan_display') // New placeholder for display
                            ->label('Total Saldo Dihasilkan')
                            ->content(function (callable $get) {
                                $value = $get('total_saldo_dihasilkan');
                                return '+ Rp ' . number_format($value, 2, ',', '.');
                            }),

                        Forms\Components\TextInput::make('total_poin_dihasilkan')
                            ->label('Total Poin Dihasilkan')
                            ->numeric()
                            ->hidden() // Make it hidden
                            ->dehydrated(true), // Ensure it's saved to DB

                        Forms\Components\Placeholder::make('total_poin_dihasilkan_display') // New placeholder for display
                            ->label('Total Poin Dihasilkan')
                            ->content(function (callable $get) {
                                $value = $get('total_poin_dihasilkan');
                                return '+ ' . number_format($value, 0, ',', '.') . ' Poin';
                            }),
                    ])
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('rekening.id')->label('Rekening')->sortable()->searchable(),
                TextColumn::make('sampah.jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(), // Display jenis_sampah
                TextColumn::make('berat')->label('Berat (gram/mL)')->sortable(),
                TextColumn::make('total_saldo_dihasilkan')->label('Total Saldo Dihasilkan')->sortable()->money('IDR'),
                TextColumn::make('total_poin_dihasilkan')->label('Total Poin Dihasilkan')->sortable(),
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
            'index' => Pages\ListSetorSampahs::route('/'),
            'create' => Pages\CreateSetorSampah::route('/create'),
            'edit' => Pages\EditSetorSampah::route('/{record}/edit'),
        ];
    }
}
