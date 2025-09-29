<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampahKeluarResource\Pages;
use App\Models\Sampah;
use App\Models\SampahKeluar;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class SampahKeluarResource extends Resource
{
    protected static ?string $model = SampahKeluar::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Operasional Bank Sampah';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Sampah Keluar')
                    ->schema([
                        Forms\Components\Select::make('jenis_keluar')
                            ->label('Jenis Sampah Keluar')
                            ->options(['jual' => 'Jual Sampah', 'bakar' => 'Bakar Sampah'])
                            ->required()
                            ->default('jual')
                            ->live(),
                        Forms\Components\DatePicker::make('tanggal_keluar')
                            ->label('Tanggal Keluar')
                            ->default(now())
                            ->required(),
                    ])->columns(2),

                Section::make('Stok Sampah Tersedia')
                    ->schema([
                        Forms\Components\Placeholder::make('stok_sampah')
                            ->label('Daftar Stok Sampah Saat Ini')
                            ->content(function () {
                                $sampahItems = Sampah::where('total_berat_terkumpul', '>', 0)->orderBy('jenis_sampah')->get();

                                if ($sampahItems->isEmpty()) {
                                    return new HtmlString('<p class="text-gray-500">Tidak ada stok sampah yang tersedia saat ini.</p>');
                                }

                                $table = '<table style="width: 100%; border-collapse: collapse; table-layout: fixed;">';
                                $table .= '<thead><tr style="background-color: #f3f4f6;"><th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Jenis Sampah</th><th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Berat Tersedia (Kg)</th></tr></thead>';
                                $table .= '<tbody>';

                                foreach ($sampahItems as $item) {
                                    $table .= '<tr>';
                                    $table .= '<td style="padding: 8px; border: 1px solid #ddd;">' . e($item->jenis_sampah) . '</td>';
                                    $table .= '<td style="padding: 8px; border: 1px solid #ddd; text-align: right;">' . number_format($item->total_berat_terkumpul, 2, ',', '.') . '</td>';
                                    $table .= '</tr>';
                                }

                                $table .= '</tbody></table>';
                                return new HtmlString($table);
                            })->columnSpanFull(),
                    ]),

                Section::make('Detail Sampah')
                    ->schema([
                        Repeater::make('details')
                            ->relationship()
                            ->label('Item Sampah')
                            ->schema([
                                Forms\Components\Select::make('sampah_id')
                                    ->relationship('sampah', 'jenis_sampah')
                                    ->label('Jenis Sampah')
                                    ->required()->preload()->searchable()->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateHydrated(function (Forms\Components\Select $component, $state) {
                                        // Simpan nilai awal untuk validasi di langkah selanjutnya
                                        $component->state($state);
                                    })
                                    ->columnSpan(['md' => 2]),
                                Forms\Components\TextInput::make('berat')
                                    ->label('Berat')
                                    ->helperText(function (Get $get) {
                                        $sampahId = $get('sampah_id');
                                        if (!$sampahId) {
                                            return 'Pilih jenis sampah terlebih dahulu.';
                                        }
                                        $beratTersedia = \App\Models\Sampah::find($sampahId)?->total_berat_terkumpul ?? 0;
                                        return 'Tidak boleh lebih dari: ' . number_format($beratTersedia, 2, ',', '.') . ' Kg';
                                    })
                                    ->postfix('Kg')->numeric()->required()->minValue(0.01)
                                    ->columnSpan(['md' => 1]),
                                // --- INPUT HARGA KONDISIONAL ---
                                Forms\Components\Hidden::make('description')
                                    ->default('Sampah Keluar')
                                    ->dehydrated(true),
                                Forms\Components\Hidden::make('type')
                                    ->default('keluar')
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('harga_jual')
                                    ->label('Uang Hasil Penjualan')
                                    ->prefix('Rp')->numeric()->required()->minValue(0)
                                    ->hidden(fn(Get $get) => $get('../../jenis_keluar') !== 'jual') // Akses state di luar repeater
                                    ->columnSpan(['md' => 1])
                                    ->dehydrated(false),
                            ])
                            ->columns(['md' => 4])
                            ->reorderable(false)
                            ->addActionLabel('Tambah Jenis Sampah')
                            ->defaultItems(1)->minItems(1)
                            ->required()
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                                $rekeningId = $get('rekening_id');


                                // Deteksi apakah $data adalah list/array of items atau single associative array
                                $isList = array_keys($data) === range(0, count($data) - 1);

                                if ($isList) {
                                    foreach ($data as &$item) {
                                        $item['rekening_id'] = $rekeningId;

                                        if (empty($item['description'])) {
                                            $item['description'] = 'Sampah Keluar';
                                        }
                                    }
                                    unset($item); // good practice setelah foreach by-ref
                    
                                    return $data;
                                }

                                // Single item case
                                $data['rekening_id'] = $rekeningId;

                                if (empty($data['description'])) {
                                    $data['description'] = 'Sampah Keluar';
                                }

                                if (empty($data['type'])) {
                                    $data['type'] = 'keluar';
                                }

                                return $data;
                            })
                        ,
                    ]),

                // --- BAGIAN PERHITUNGAN KONDISIONAL ---
                Section::make('Perhitungan dan Rincian')
                    ->hidden(fn(Get $get) => $get('jenis_keluar') !== 'jual') // Sembunyikan jika 'bakar'
                    ->schema([
                        Forms\Components\Hidden::make('rekening_id')
                            ->default(function () {
                                $rekening = \App\Models\Rekening::where('no_rekening', '00000000')->first();
                                return $rekening?->id;
                            }), // Tidak perlu disimpan di DB
                        Forms\Components\Hidden::make('calculation_performed')->default(false)->dehydrated(true),
                        Forms\Components\Hidden::make('total_saldo_dihasilkan')->default(0),
                        Forms\Components\Hidden::make('berat_keluar')->default(0),
                        Forms\Components\Hidden::make('user_id')->default(auth()->id()),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('hitung_jual')
                                ->label('Hitung Total Penjualan')
                                ->color('success')
                                ->action(function (Get $get, Set $set) {
                                    self::updateTotalsJual($get, $set);
                                    $set('calculation_performed', true);
                                }),
                        ])->columnSpanFull(),

                        Forms\Components\Placeholder::make('rincian_penjualan')
                            ->label('Rincian')
                            ->visible(fn(Get $get) => (bool) $get('calculation_performed'))
                            ->content(function (Get $get) {
                                $items = $get('details');
                                $sampahData = Sampah::whereIn('id', array_column($items, 'sampah_id'))->get()->keyBy('id');
                                return self::generateRincianHtmlJual($items, $sampahData);
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $data['details'] ?? [];
        $totalBerat = 0;
        foreach ($items as $item) {
            $totalBerat += (float) ($item['berat'] ?? 0);
        }
        $data['berat_keluar'] = $totalBerat;
        $data['user_id'] = auth()->id();

        if ($data['jenis_keluar'] === 'jual') {
            if (!($data['calculation_performed'] ?? false)) {
                Notification::make()->title('Perhitungan Belum Dilakukan')->body('Anda harus menekan tombol "Hitung Total Penjualan" sebelum menyimpan.')->danger()->send();
                throw \Illuminate\Validation\ValidationException::withMessages(['hitung_jual' => 'Tombol hitung harus ditekan.']);
            }
        } else { // jenis_keluar === 'bakar'
            $data['total_saldo_dihasilkan'] = 0;
        }
        return $data;
    }

    public static function updateTotalsJual(Get $get, Set $set): void
    {
        $items = $get('details');
        $totalHarga = 0;
        $totalBerat = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                $totalBerat += (float) ($item['berat'] ?? 0);
                $totalHarga += (float) ($item['harga_jual'] ?? 0);
            }
        }
        $set('total_saldo_dihasilkan', $totalHarga);
        $set('berat_keluar', $totalBerat);
    }

    public static function mutateRelationshipDataBeforeCreate(array $data): array
    {
        $sampah = Sampah::find($data['sampah_id']);
        if ($sampah && isset($data['berat'])) {
            // Pastikan berat yang dimasukkan tidak melebihi total berat terkumpul
            if ($data['berat'] > $sampah->berat_keluar_terkumpul) {
                Notification::make()
                    ->title('Berat Melebihi Batas')
                    ->body('Berat yang dimasukkan melebihi total berat terkumpul untuk jenis sampah ini.')
                    ->danger()->send();
            }
        }
        return $data;
    }

    public static function generateRincianHtmlJual(array $items, Collection $sampahData): HtmlString
    {
        $totalHarga = 0;
        $totalBerat = 0;
        $rows = '';
        foreach ($items as $item) {
            if (empty($item['sampah_id']) || empty($item['berat']) || !is_numeric($item['berat']))
                continue;
            $sampah = $sampahData->get($item['sampah_id']);
            if ($sampah) {
                $berat = (float) $item['berat'];
                $harga = (float) ($item['harga_jual'] ?? 0);
                $hargaPerKg = ($berat > 0) ? ($harga / $berat) : 0;
                $rows .= "<tr>
                            <td style='padding:6px;'>{$sampah->jenis_sampah}</td>
                            <td style='padding:6px; text-align:center;'>{$berat} Kg</td>
                            <td style='padding:6px; text-align:right;'>Rp " . number_format($hargaPerKg, 2, ',', '.') . "</td>
                            <td style='padding:6px; text-align:right;'>Rp " . number_format($harga, 2, ',', '.') . "</td>
                        </tr>";
                $totalHarga += $harga;
                $totalBerat += $berat;
            }
        }
        $avgHargaPerKg = ($totalBerat > 0) ? ($totalHarga / $totalBerat) : 0;
        $totalRow = "<tr style='font-weight:bold; border-top:2px solid #333;'>
                        <td style='padding:6px;'>Total</td>
                        <td style='padding:6px; text-align:center;'>" . number_format($totalBerat, 2, ',', '.') . " Kg</td>
                        <td style='padding:6px; text-align:right;'>Rp " . number_format($avgHargaPerKg, 2, ',', '.') . " (Rata-rata)</td>
                        <td style='padding:6px; text-align:right;'>Rp " . number_format($totalHarga, 2, ',', '.') . "</td>
                    </tr>";
        $header = "<thead><tr style='background:#f3f4f6; text-align:left;'><th style='padding:6px;'>Jenis Sampah</th><th style='padding:6px; text-align:center;'>Berat</th><th style='padding:6px; text-align:right;'>Harga/Kg</th><th style='padding:6px; text-align:right;'>Hasil Jual</th></tr></thead>";
        return new HtmlString("<table style='width:100%; border-collapse:collapse;'>{$header}<tbody>{$rows}{$totalRow}</tbody></table>");
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_keluar')->label('Jenis')->sortable()->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'jual' => 'success',
                        'bakar' => 'danger',
                    }),
                TextColumn::make('tanggal_keluar')->date()->label('Tanggal')->sortable(),
                TextColumn::make('berat_keluar')->label('Total Berat (Kg)')->sortable()->weight('bold'),
                TextColumn::make('total_saldo_dihasilkan')->label('Total Hasil Jual')->sortable()->money('IDR')
                    ->formatStateUsing(fn(string $state) => $state > 0 ? "Rp " . number_format($state, 0, ',', '.') : '-'),
                TextColumn::make('user.name')->label('Petugas')->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Dibuat')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_keluar', 'desc')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSampahKeluars::route('/'),
            'create' => Pages\CreateSampahKeluar::route('/create'),
            'edit' => Pages\EditSampahKeluar::route('/{record}/edit'),
        ];
    }
}
