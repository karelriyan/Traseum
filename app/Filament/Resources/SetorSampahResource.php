<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetorSampahResource\Pages;
use App\Models\SetorSampah;
use App\Models\Rekening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class SetorSampahResource extends Resource
{
    protected static ?string $model = SetorSampah::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Operasional Bank Sampah';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Jenis Setoran')
                    ->schema([
                        Forms\Components\Select::make('jenis_setoran')
                            ->label('Pilih Jenis Setoran')
                            ->options([
                                'rekening' => 'Rekening Nasabah',
                                'donasi' => 'Donasi',
                            ])
                            ->required()
                            ->live(), // <-- Penting untuk membuat form reaktif

                        Forms\Components\Select::make('rekening_id')
                            ->label('Pilih Rekening Nasabah')
                            ->preload()
                            ->searchable()
                            ->dehydrated(true)
                            // Hanya tampil dan wajib diisi jika jenis setoran adalah 'rekening'
                            ->hidden(fn(Get $get) => $get('jenis_setoran') != 'rekening')
                            ->required(fn(Get $get) => $get('jenis_setoran') === 'rekening')
                            ->validationMessages([
                                'required' => 'Rekening nasabah tidak boleh kosong'
                            ])
                            ->options(function () {
                                // Ambil semua rekening KECUALI rekening donasi
                                return Rekening::query()
                                    ->where('no_rekening', '!=', '00000000000000')
                                    ->select('id', 'nama', 'nik')
                                    ->get()
                                    ->mapWithKeys(fn($rekening) => [$rekening->id => "{$rekening->nama} - {$rekening->nik}"]);
                            }),
                    ]),
                Section::make('Informasi Setoran Sampah')
                    ->schema([
                        Repeater::make('detailSetorSampah')
                            ->relationship('details')
                            ->label('Item Sampah')
                            ->schema([
                                Forms\Components\Select::make('sampah_id')
                                    ->relationship('sampah', 'jenis_sampah')
                                    ->label('Jenis Sampah')
                                    ->required()
                                    ->preload()
                                    ->searchable()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(['md' => 2]),
                                Forms\Components\TextInput::make('berat')
                                    ->label('Berat')
                                    ->postfix('Kg')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->columnSpan(['md' => 1]),
                            ])
                            ->columns(3)
                            ->live()
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updateTotals($get, $set))
                            ->addAction(fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)))
                            ->deleteAction(fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)))
                            ->reorderable(false)
                            ->addActionLabel('Tambah Jenis Sampah')
                            ->defaultItems(1)
                            ->itemLabel(fn(array $state): ?string => \App\Models\Sampah::find($state['sampah_id'])?->jenis_sampah ?? null),
                    ]),
                Section::make('Total Perhitungan')
                    ->schema([
                        Forms\Components\Hidden::make('calculation_performed')
                            ->default(false),
                        Forms\Components\Hidden::make('total_saldo_dihasilkan')
                            ->default(0),
                        Forms\Components\Hidden::make('total_poin_dihasilkan')
                            ->default(0),
                        Forms\Components\Hidden::make('berat')
                            ->default(0),
                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),

                        Forms\Components\Placeholder::make('total_berat_placeholder')
                            ->label('Total Berat')
                            ->content(function (Get $get) {
                                $total = $get('berat');
                                return $total ? number_format($total, 2, ',', '.') . ' Kg' : '0 Kg';
                            }),

                        Forms\Components\Placeholder::make('total_saldo_dihasilkan_placeholder')
                            ->label('Total Saldo Dihasilkan')
                            ->content(function (Get $get) {
                                $total = $get('total_saldo_dihasilkan');
                                return $total ? 'Rp ' . number_format($total, 2, ',', '.') : 'Rp 0';
                            }),

                    ])->columns(3),
            ]);
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $items = $get('detailSetorSampah');
        $totalSaldo = 0;
        $totalPoin = 0;
        $totalBerat = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                if (empty($item['sampah_id']) || empty($item['berat']) || !is_numeric($item['berat'])) {
                    continue;
                }

                $sampah = \App\Models\Sampah::find($item['sampah_id']);
                if ($sampah) {
                    $berat = (float) $item['berat'];
                    $totalBerat += $berat;
                    $totalSaldo += $sampah->saldo_per_kg * $berat;
                    $totalPoin += $sampah->poin_per_kg * $berat;
                }
            }
        }

        $set('total_saldo_dihasilkan', round($totalSaldo, 2));
        $set('total_poin_dihasilkan', round($totalPoin));
        $set('berat', round($totalBerat, 4));
        $set('calculation_performed', count($items ?? []) > 0 && $totalBerat > 0);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rekening.nama')
                    ->label('Nasabah')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->rekening?->no_rekening === '00000000000000') {
                            return 'Donasi'; // tampilkan teks badge
                        }
                        return $state; // tampilkan nama asli
                    })
                    ->badge(fn($state, $record) => $record->rekening?->no_rekening === '00000000000000') // badge hanya kalau donasi
                    ->color(fn($state, $record) => $record->rekening?->no_rekening === '00000000000000' ? 'success' : null),

                TextColumn::make('details.sampah.jenis_sampah')
                    ->label('Item Sampah')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('berat')->label('Total Berat (Kg)')->sortable()->weight('bold'),
                TextColumn::make('total_saldo_dihasilkan')->label('Total Saldo')->sortable()->money('IDR'),
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
