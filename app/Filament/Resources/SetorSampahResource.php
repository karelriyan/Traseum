<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetorSampahResource\Pages;
use App\Models\SetorSampah;
use App\Models\Rekening;
use App\Models\Sampah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Collection;

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
                Section::make('Informasi Penyetor')
                    ->schema([
                        Forms\Components\Select::make('jenis_setoran')
                            ->label('Pilih Jenis Setoran')
                            ->options([
                                'rekening' => 'Rekening Nasabah',
                                'donasi' => 'Donasi',
                            ])
                            ->required()
                            ->default('rekening')
                            ->live(), // Live di sini aman karena hanya mengontrol satu field lain

                        Forms\Components\Select::make('rekening_id')
                            ->label('Pilih Rekening Nasabah')
                            ->relationship('rekening', 'nama', modifyQueryUsing: fn($query) => $query->where('no_rekening', '!=', '00000000'))
                            ->getOptionLabelFromRecordUsing(fn(Rekening $record) => "{$record->nama} - {$record->nik}")
                            ->searchable(['nama', 'nik'])
                            ->preload()
                            ->hidden(fn(Get $get) => $get('jenis_setoran') !== 'rekening')
                            ->required(fn(Get $get) => $get('jenis_setoran') === 'rekening'),

                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Setoran')
                            ->required(),
                    ])->columns(1),

                Section::make('Detail Setoran Sampah')
                    ->schema([
                        Repeater::make('details')
                            ->relationship()
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
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('berat')
                                    ->label('Berat')
                                    ->postfix('Kg')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            // DIHAPUS: Atribut live() dan afterStateUpdated() untuk mencegah reload otomatis
                            ->reorderable(false)
                            ->addActionLabel('Tambah Jenis Sampah')
                            ->defaultItems(1)
                            ->minItems(1)
                            ->required()
                            ->mutateRelationshipDataBeforeCreate(function (array $data, Get $get): array {
                                if ($get('jenis_setoran') === 'donasi') {
                                    $rekeningDonasi = Rekening::where('no_rekening', '00000000')->first();
                                    $data['rekening_id'] = $rekeningDonasi?->id;
                                } else {
                                    $data['rekening_id'] = $get('rekening_id');
                                }
                                return $data;
                            }),
                    ]),

                Section::make('Perhitungan dan Rincian')
                    ->schema([
                        // DIKEMBALIKAN: Field tersembunyi untuk melacak status perhitungan
                        Forms\Components\Hidden::make('calculation_performed')->default(false)->dehydrated(true),
                        Forms\Components\Hidden::make('total_saldo_dihasilkan')->default(0),
                        Forms\Components\Hidden::make('total_poin_dihasilkan')->default(0),
                        Forms\Components\Hidden::make('berat')->default(0),
                        Forms\Components\Hidden::make('user_id')->default(auth()->id()),

                        // DIKEMBALIKAN: Tombol hitung manual
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('hitung')
                                ->label('Hitung Total')
                                ->color('success')
                                ->icon('heroicon-o-calculator')
                                ->action(function (Get $get, Set $set) {
                                    // Validasi data sebelum perhitungan
                                    $items = $get('details');

                                    if (!is_array($items) || empty($items)) {
                                        Notification::make()
                                            ->title('Data Belum Lengkap')
                                            ->body('Silakan tambahkan item sampah terlebih dahulu.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    $validItems = array_filter($items, function ($item) {
                                        return !empty($item['sampah_id']) && !empty($item['berat']) && is_numeric($item['berat']);
                                    });

                                    if (empty($validItems)) {
                                        Notification::make()
                                            ->title('Data Tidak Valid')
                                            ->body('Pastikan jenis sampah dan berat sudah diisi dengan benar.')
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    // Lakukan perhitungan
                                    self::updateTotals($get, $set);
                                    $set('calculation_performed', true); // Set flag bahwa perhitungan sudah dilakukan
                        
                                    // Notifikasi sukses
                                    Notification::make()
                                        ->title('Perhitungan Berhasil')
                                        ->body('Total telah dihitung. Silakan periksa rincian di bawah.')
                                        ->success()
                                        ->send();
                                }),
                        ])->columnSpanFull(),

                        // Placeholder rincian, hanya muncul SETELAH tombol 'Hitung' ditekan
                        Forms\Components\Placeholder::make('rincian_placeholder')
                            ->label('Rincian')
                            ->visible(fn(Get $get) => (bool) $get('calculation_performed')) // <-- Hanya tampil jika perhitungan selesai
                            ->content(function (Get $get) {
                                $items = $get('details');
                                $jenisSetoran = $get('jenis_setoran');

                                // Debug: Log data untuk troubleshooting
                                \Log::info('Calculation Debug', [
                                    'items' => $items,
                                    'calculation_performed' => $get('calculation_performed'),
                                    'jenis_setoran' => $jenisSetoran
                                ]);

                                if (!is_array($items) || empty($items)) {
                                    return new HtmlString('<p class="text-sm text-red-500">Belum ada item sampah yang ditambahkan.</p>');
                                }

                                // Periksa apakah ada item yang valid
                                $validItems = array_filter($items, function ($item) {
                                    return !empty($item['sampah_id']) && !empty($item['berat']) && is_numeric($item['berat']);
                                });

                                if (empty($validItems)) {
                                    return new HtmlString('<p class="text-sm text-red-500">Item sampah belum diisi dengan benar. Pastikan jenis sampah dan berat sudah diisi.</p>');
                                }

                                $sampahIds = array_column($validItems, 'sampah_id');
                                $sampahData = Sampah::whereIn('id', $sampahIds)->get()->keyBy('id');

                                if ($sampahData->isEmpty()) {
                                    return new HtmlString('<p class="text-sm text-red-500">Data sampah tidak ditemukan.</p>');
                                }

                                return self::generateRincianHtml($validItems, $sampahData, $jenisSetoran);
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // DIKEMBALIKAN: Lifecycle hook untuk validasi sebelum create
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Handle kasus donasi untuk memastikan rekening_id terisi (jika belum ada di model)
        if (isset($data['jenis_setoran']) && $data['jenis_setoran'] === 'donasi') {
            $rekeningDonasi = Rekening::where('no_rekening', '00000000')->first();
            if ($rekeningDonasi) {
                $data['rekening_id'] = $rekeningDonasi->id;
            }
        }

        // 2. Cek apakah perhitungan sudah dilakukan
        if (!isset($data['calculation_performed']) || !$data['calculation_performed']) {
            Notification::make()
                ->title('Perhitungan Belum Dilakukan')
                ->body('Anda harus menekan tombol "Hitung Total" sebelum menyimpan data.')
                ->danger()
                ->send();

            throw \Illuminate\Validation\ValidationException::withMessages([
                'hitung' => 'Tombol "Hitung Total" harus ditekan terlebih dahulu.',
            ]);
        }

        return $data;
    }

    // Fungsi generateRincianHtml tidak berubah, sudah baik.
    public static function generateRincianHtml(array $items, Collection $sampahData, string $jenisSetoran): HtmlString
    {
        $totalSaldo = 0;
        $totalBerat = 0;
        $rows = '';

        foreach ($items as $item) {
            if (empty($item['sampah_id']) || empty($item['berat']) || !is_numeric($item['berat']))
                continue;

            $sampah = $sampahData->get($item['sampah_id']);
            if ($sampah) {
                $berat = (float) $item['berat'];
                $saldo = $sampah->saldo_per_kg * $berat;
                $rows .= "<tr>
                            <td style='padding:6px;'>{$sampah->jenis_sampah}</td>
                            <td style='padding:6px; text-align:center;'>{$berat} Kg</td>
                            " . ($jenisSetoran !== 'donasi' ? "<td style='padding:6px; text-align:right;'>Rp " . number_format($saldo, 2, ',', '.') . "</td>" : '') . "
                        </tr>";
                $totalSaldo += $saldo;
                $totalBerat += $berat;
            }
        }

        $totalRow = "<tr style='font-weight:bold; border-top:2px solid #333;'>
                        <td style='padding:6px;'>Total</td>
                        <td style='padding:6px; text-align:center;'>" . number_format($totalBerat, 2, ',', '.') . " Kg</td>
                        " . ($jenisSetoran !== 'donasi' ? "<td style='padding:6px; text-align:right;'>Rp " . number_format($totalSaldo, 2, ',', '.') . "</td>" : '') . "
                    </tr>";

        $header = "<thead>
                    <tr text-align:left;'>
                        <th style='padding:6px;'>Jenis Sampah</th>
                        <th style='padding:6px; text-align:center;'>Berat</th>
                        " . ($jenisSetoran !== 'donasi' ? "<th style='padding:6px; text-align:right;'>Saldo</th>" : '') . "
                    </tr>
                </thead>";

        $html = "<table style='width:100%; border-collapse:collapse;'>{$header}<tbody>{$rows}{$totalRow}</tbody></table>";

        if ($jenisSetoran === 'donasi') {
            $html .= "<p class='text-sm text-green-600 mt-2'>Ini adalah transaksi donasi. Saldo tidak ditambahkan ke rekening, hanya berat yang dicatat sebagai aset bank sampah.</p>";
        }

        return new HtmlString($html);
    }

    // Fungsi updateTotals dengan debugging dan validasi yang lebih baik
    public static function updateTotals(Get $get, Set $set): void
    {
        $items = $get('details');
        $totalSaldo = 0;
        $totalPoin = 0;
        $totalBerat = 0;

        // Debug: Log data input
        \Log::info('UpdateTotals Debug', [
            'items' => $items,
            'is_array' => is_array($items)
        ]);

        if (is_array($items) && !empty($items)) {
            // Filter item yang valid
            $validItems = array_filter($items, function ($item) {
                return !empty($item['sampah_id']) && !empty($item['berat']) && is_numeric($item['berat']);
            });

            if (!empty($validItems)) {
                $sampahIds = array_column($validItems, 'sampah_id');
                $sampahData = Sampah::whereIn('id', $sampahIds)->get()->keyBy('id');

                \Log::info('Sampah Data Found', [
                    'sampah_ids' => $sampahIds,
                    'sampah_data_count' => $sampahData->count()
                ]);

                foreach ($validItems as $item) {
                    $sampah = $sampahData->get($item['sampah_id']);
                    if ($sampah) {
                        $berat = (float) $item['berat'];
                        $saldoItem = $sampah->saldo_per_kg * $berat;
                        $poinItem = $sampah->poin_per_kg * $berat;

                        $totalBerat += $berat;
                        $totalSaldo += $saldoItem;
                        $totalPoin += $poinItem;

                        \Log::info('Item Calculation', [
                            'sampah' => $sampah->jenis_sampah,
                            'berat' => $berat,
                            'saldo_per_kg' => $sampah->saldo_per_kg,
                            'poin_per_kg' => $sampah->poin_per_kg,
                            'saldo_item' => $saldoItem,
                            'poin_item' => $poinItem
                        ]);
                    }
                }
            }
        }

        $set('total_saldo_dihasilkan', round($totalSaldo));
        $set('total_poin_dihasilkan', round($totalPoin));
        $set('berat', round($totalBerat, 4));

        \Log::info('Final Totals', [
            'total_saldo' => $totalSaldo,
            'total_poin' => $totalPoin,
            'total_berat' => $totalBerat
        ]);
    }

    // ... sisa dari resource (table, getPages) tidak perlu diubah ...
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rekening.nama')
                    ->label('Nasabah / Jenis')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => $record->isDonation() ? 'Donasi' : $state)
                    ->badge()
                    ->color(fn($record) => $record->isDonation() ? 'success' : 'gray'),

                TextColumn::make('details.sampah.jenis_sampah')
                    ->label('Item Sampah')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('berat')->label('Total Berat (Kg)')->sortable()->weight('bold'),
                TextColumn::make('total_saldo_dihasilkan')->label('Total Saldo')->sortable()->money('IDR'),
                TextColumn::make('user.name')->label('Petugas')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime()->label('Dibuat')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
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
