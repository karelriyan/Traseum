<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetorSampahResource\Pages;
use App\Models\SetorSampah;
use App\Models\Rekening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;

class SetorSampahResource extends Resource
{
    protected static ?string $model = SetorSampah::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Operasional Bank Sampah';

    protected static ?int $navigationSort = 1;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->disabled(fn($livewire): bool => !$livewire->data['calculation_performed']),
            $this->getCreateAnotherFormAction()
                ->disabled(fn($livewire): bool => !$livewire->data['calculation_performed']),
            $this->getCancelFormAction(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Sampah Masuk')
                    ->schema([
                        Forms\Components\Select::make('rekening_id')
                            ->label('Pilih Rekening')
                            ->required()
                            ->searchable()
                            ->validationMessages([
                                'required' => 'Rekening tidak boleh kosong'
                            ])
                            ->options(function () {
                                return Rekening::query()
                                    ->select('id', 'nama', 'nik')
                                    ->get()
                                    ->mapWithKeys(fn($rekening) => [$rekening->id => "{$rekening->nama} - {$rekening->nik}"]);
                            }),
                        Forms\Components\Select::make('sampah_id')
                            ->relationship('sampah', 'jenis_sampah')
                            ->label('Jenis Sampah')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->reactive(),
                        Forms\Components\TextInput::make('berat')
                            ->label('Berat')
                            ->postfix('Kg')
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (callable $set) {
                                // Reset hasil hitung setiap kali berat diubah
                                $set('calculation_performed', false);
                                $set('total_saldo_dihasilkan', 0);
                                $set('total_poin_dihasilkan', 0);
                            }),
                    ]),

                Section::make('Perhitungan Penambahan Saldo')
                    ->schema([
                        Forms\Components\Actions::make([
                            Action::make('hitung')
                                ->label('Hitung')
                                ->action(function (callable $get, callable $set) {
                                    $sampah_id = $get('sampah_id');
                                    $berat = (float) $get('berat');

                                    if (!$sampah_id || !$berat || $berat <= 0) {
                                        $set('total_saldo_dihasilkan', 0);
                                        $set('total_poin_dihasilkan', 0);
                                        return;
                                    }

                                    $sampah = \App\Models\Sampah::find($sampah_id);
                                    if ($sampah) {
                                        $berat_kg_liter = $berat;
                                        $total_saldo = $sampah->saldo_per_kg * $berat_kg_liter;
                                        $total_poin = $sampah->poin_per_kg * $berat_kg_liter;

                                        $set('total_saldo_dihasilkan', $total_saldo);
                                        $set('total_poin_dihasilkan', $total_poin);
                                        $set('calculation_performed', true);
                                    } else {
                                        $set('total_saldo_dihasilkan', 0);
                                        $set('total_poin_dihasilkan', 0);
                                        $set('calculation_performed', false);
                                    }
                                })
                                ->color('primary')
                                ->icon('heroicon-o-calculator'),
                        ]),

                        Forms\Components\Hidden::make('calculation_performed')
                            ->default(false),

                        Forms\Components\Placeholder::make('total_saldo_placeholder')
                            ->label('Berat')
                            ->visible(fn(callable $get): bool => (bool) $get('calculation_performed'))
                            ->content(function (callable $get) {
                                $total = $get('berat');
                                return $total ? number_format($total, 0, ',', '.') . ' gram' : '0 gram';
                            }),


                        Forms\Components\Placeholder::make('total_saldo_placeholder')
                            ->label('Total Saldo Dihasilkan')
                            ->visible(fn(callable $get): bool => (bool) $get('calculation_performed'))
                            ->content(function (callable $get) {
                                $total = $get('total_saldo_dihasilkan');
                                return $total ? 'Rp ' . number_format($total, 0, ',', '.') : 'Rp 0';
                            }),

                        Forms\Components\Placeholder::make('total_poin_placeholder')
                            ->label('Total Poin Dihasilkan')
                            ->visible(fn(callable $get): bool => (bool) $get('calculation_performed'))
                            ->content(function (callable $get) {
                                $total = $get('total_poin_dihasilkan');
                                return $total ? number_format($total, 0, ',', '.') . ' Poin' : '0 Poin';
                            }),

                        Forms\Components\Hidden::make('total_saldo_dihasilkan')
                            ->default(0),

                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rekening.nama')->label('Nasabah')->sortable()->searchable(),
                TextColumn::make('rekening.no_kk')->label('Nomor KK')->sortable()->searchable(),
                TextColumn::make('sampah.jenis_sampah')->label('Jenis Sampah')->sortable()->searchable(),
                TextColumn::make('berat')->label('Berat (Kg)')->sortable(),
                TextColumn::make('total_saldo_dihasilkan')->label('Saldo Didapatkan')->sortable()->money('IDR'),
                TextColumn::make('total_poin_dihasilkan')->label('Poin Didapatkan')->sortable(),
                TextColumn::make('user.name')->label('Penyetor')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime()->label('Dibuat')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->label('Diubah')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
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
