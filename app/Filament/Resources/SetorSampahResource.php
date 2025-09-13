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
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

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
                            ->default(fn(Get $get) => Rekening::where('no_rekening', '00000000')->first()?->id)
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
                                    ->where('no_rekening', '!=', '00000000')
                                    ->select('id', 'nama', 'nik')
                                    ->get()
                                    ->mapWithKeys(fn($rekening) => [$rekening->id => "{$rekening->nama} - {$rekening->nik}"]);
                            }),
                    ]),
                Section::make('Informasi Setoran Sampah')
                    ->schema([
                        Repeater::make('details')
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
                                    ->minValue(0.0001)
                                    ->step(0.0001)
                                    ->columnSpan(['md' => 1]),
                            ])
                            ->columns(3)
                            ->addAction(fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)))
                            ->deleteAction(fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)))
                            ->reorderable(false)
                            ->addActionLabel('Tambah Jenis Sampah')
                            ->defaultItems(1)
                            ->minItems(1)
                            ->required()
                            ->itemLabel(fn(array $state): ?string => \App\Models\Sampah::find($state['sampah_id'])?->jenis_sampah ?? null)
                            ->validationMessages([
                                'required' => 'Sampah tidak boleh kosong',
                            ]),
                    ]),

                Section::make('Total Perhitungan')
                    ->schema([
                        // Flag ini HANYA untuk melacak status. Validasi telah dipindahkan ke mutateFormDataBeforeCreate.
                        Forms\Components\Hidden::make('calculation_performed')
                            ->default(false)
                            ->dehydrated(true),

                        Forms\Components\Hidden::make('total_saldo_dihasilkan')->default(0),
                        Forms\Components\Hidden::make('total_poin_dihasilkan')->default(0),
                        Forms\Components\Hidden::make('berat')->default(0),
                        Forms\Components\Hidden::make('user_id')->default(auth()->id()),

                        // Tombol hitung
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('hitung')
                                ->label('Hitung Total')
                                ->color('success')
                                ->action(function (Get $get, Set $set) {
                                    self::updateTotals($get, $set);
                                    $set('calculation_performed', true);
                                }),
                        ]),

                        // Placeholder hasil rinci → hanya muncul kalau calculation_performed = true
                        Forms\Components\Placeholder::make('rincian_placeholder')
                            ->label('Rincian Perhitungan')
                            ->visible(fn(Get $get) => (bool) $get('calculation_performed'))
                            ->content(function (Get $get) {
                                $items = $get('details');
                                if (!is_array($items) || empty($items)) {
                                    return new HtmlString('<p><em>Belum ada item sampah.</em></p>');
                                }

                                $totalSaldo = 0;
                                $totalBerat = 0;
                                $rows = '';

                                foreach ($items as $item) {
                                    if (empty($item['sampah_id']) || empty($item['berat']) || !is_numeric($item['berat'])) {
                                        continue;
                                    }

                                    $sampah = \App\Models\Sampah::find($item['sampah_id']);
                                    if ($sampah) {
                                        $berat = (float) $item['berat'];
                                        $saldo = $sampah->saldo_per_kg * $berat;

                                        $rows .= "
                                            <tr>
                                                <td style='padding:6px;'>{$sampah->jenis_sampah}</td>
                                                <td style='padding:6px; text-align:center;'>{$berat} Kg</td>
                                                <td style='padding:6px; text-align:right;'>Rp " . number_format($saldo, 2, ',', '.') . "</td>
                                            </tr>
                                        ";

                                        $totalSaldo += $saldo;
                                        $totalBerat += $berat;
                                    }
                                }

                                $rows .= "
                                    <tr style='font-weight:bold; border-top:2px solid #333;'>
                                        <td style='padding:6px;'>Total</td>
                                        <td style='padding:6px; text-align:center;'>" . number_format($totalBerat, 2, ',', '.') . " Kg</td>
                                        <td style='padding:6px; text-align:right;'>Rp " . number_format($totalSaldo, 2, ',', '.') . "</td>
                                    </tr>
                                ";

                                $html = "
                                    <table style='width:100%; border-collapse:collapse;'>
                                        <thead>
                                            <tr style='background:#f3f4f6; text-align:left;'>
                                                <th style='padding:6px;'>Jenis Sampah</th>
                                                <th style='padding:6px; text-align:center;'>Berat</th>
                                                <th style='padding:6px; text-align:right;'>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$rows}
                                        </tbody>
                                    </table>
                                ";

                                return new HtmlString($html);
                            })
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'prose max-w-none']),


                    ])
                    ->columns(3),

            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Handle kasus donasi untuk memastikan rekening_id terisi
        if (isset($data['jenis_setoran']) && $data['jenis_setoran'] === 'donasi') {
            $rekeningDonasi = Rekening::where('no_rekening', '00000000')->first();
            if ($rekeningDonasi) {
                $data['rekening_id'] = $rekeningDonasi->id;
            } else {
                // Berhenti dan beri notifikasi jika rekening donasi tidak ditemukan
                Notification::make()
                    ->title('Error Konfigurasi')
                    ->body('Rekening untuk donasi tidak ditemukan. Harap hubungi administrator.')
                    ->danger()
                    ->send();

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jenis_setoran' => 'Rekening donasi tidak valid.',
                ]);
            }
        }

        // 2. Cek apakah perhitungan sudah dilakukan
        if (!isset($data['calculation_performed']) || !$data['calculation_performed']) {
            // Kirim notifikasi error
            Notification::make()
                ->title('Perhitungan Belum Dilakukan')
                ->body('Anda harus menekan tombol "Hitung Total" sebelum menyimpan data.')
                ->danger()
                ->send();

            // Hentikan proses penyimpanan dengan melempar exception
            throw \Illuminate\Validation\ValidationException::withMessages([
                'calculation_performed' => 'Anda harus menekan tombol Hitung Total terlebih dahulu.',
            ]);
        }

        return $data;
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        // PERBAIKAN: Menggunakan 'details' sesuai nama repeater
        $items = $get('details');
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
                        if ($record->rekening?->no_rekening === '00000000') {
                            return 'Donasi'; // tampilkan teks badge
                        }
                        return $state; // tampilkan nama asli
                    })
                    ->badge(fn($state, $record) => $record->rekening?->no_rekening === '00000000') // badge hanya kalau donasi
                    ->color(fn($state, $record) => $record->rekening?->no_rekening === '00000000' ? 'success' : null),

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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSetorSampahs::route('/'),
            'create' => Pages\CreateSetorSampah::route('/create'),
            'view' => Pages\ViewSetorSampah::route('/{record}'),
            'edit' => Pages\EditSetorSampah::route('/{record}/edit'),
        ];
    }
}

