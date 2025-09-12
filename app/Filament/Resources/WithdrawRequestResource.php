<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawRequestResource\Pages;
use App\Models\Rekening;
use App\Models\SaldoTransaction;
use App\Models\WithdrawRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;

class WithdrawRequestResource extends Resource
{
    protected static ?string $model = WithdrawRequest::class;
    protected static ?string $title = 'Penarikan Saldo';
    protected static ?string $pluralModelLabel = 'Penarikan Saldo';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square';

    protected static ?string $navigationLabel = 'Penarikan Saldo';

    protected static ?string $navigationGroup = 'Operasional Bank Sampah';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Rekening')
                    ->schema([
                        Forms\Components\Select::make('rekening_id')
                            ->relationship('rekening', 'nama')
                            ->label('Rekening Nasabah')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Rekening::query()
                                    ->where('no_rekening', '!=', '00000000000000')
                                    ->select('id', 'nama', 'nik')
                                    ->get()
                                    ->mapWithKeys(fn($rekening) => [$rekening->id => "{$rekening->nama} - {$rekening->nik}"]);
                            })
                            ->live()
                            ->afterStateHydrated(function ($state, Forms\Set $set) {
                                $rekening = $state ? Rekening::find($state) : null;
                                $set('current_balance', $rekening?->balance ?? 0);
                                $set('formatted_balance', 'Rp ' . number_format($rekening?->balance ?? 0, 0, ',', '.'));
                            })
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $rekening = $state ? Rekening::find($state) : null;
                                $set('current_balance', $rekening?->balance ?? 0);
                                $set('formatted_balance', 'Rp ' . number_format($rekening?->balance ?? 0, 0, ',', '.'));
                            }),
                        Forms\Components\Placeholder::make('saldo_info')
                            ->label('Saldo Tersedia')
                            ->content(function (Forms\Get $get) {
                                if ($rekeningId = $get('rekening_id')) {
                                    $rekening = Rekening::find($rekeningId);
                                    return $rekening ? 'Rp ' . number_format($rekening->balance, 0, ',', '.') : '-';
                                }
                                return '-';
                            }),
                        Forms\Components\Placeholder::make('tabungan_emas_info')
                            ->label('Kepemilikan Tabungan Emas')
                            ->content(function (Forms\Get $get) {
                                if ($rekeningId = $get('rekening_id')) {
                                    $rekening = Rekening::find($rekeningId);
                                    return $rekening ? ($rekening->status_pegadaian ? 'Ada' : 'Tidak Ada') : '-';
                                }
                                return '-';
                            }),
                        Forms\Components\Hidden::make('current_balance'),
                        Forms\Components\Hidden::make('formatted_balance'),
                    ]),

                Section::make('Detail Penarikan')
                    ->schema([
                        Select::make('jenis')
                            ->label('Metode Penarikan')
                            ->required()
                            ->live()
                            ->options(['cash' => 'Cash', 'emas' => 'Tabungan Emas Pegadaian']),

                        Toggle::make('is_new_pegadaian_registration')
                            ->label('Daftarkan nasabah ini untuk Tabungan Emas Pegadaian?')
                            ->live()
                            ->visible(function (Get $get): bool {
                                $rekeningId = $get('rekening_id');
                                if (!$rekeningId || $get('jenis') !== 'emas')
                                    return false;
                                $rekening = Rekening::find($rekeningId);
                                return $rekening && !$rekening->status_pegadaian;
                            }),

                        // DITAMBAHKAN: Input untuk nomor rekening baru
                        TextInput::make('no_rek_pegadaian')
                            ->label('Nomor Rekening Pegadaian Baru')
                            ->numeric()
                            ->maxLength(16)
                            ->unique(table: Rekening::class, column: 'no_rek_pegadaian')
                            ->visible(fn(Get $get) => (bool) $get('is_new_pegadaian_registration'))
                            ->nullable()
                            ->validationMessages([
                                'required' => 'Nomor rekening baru wajib diisi jika mendaftar.',
                                'unique' => 'Nomor rekening ini sudah terdaftar di nasabah lain.'
                            ]),

                        TextInput::make('amount')
                            ->label('Jumlah Penarikan')
                            ->numeric()->required()->prefix('Rp')->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')->minValue(10000)->maxValue(fn(Get $get) => (float) ($get('current_balance') ?? 0))
                            ->visible(function (Get $get): bool {
                                $jenis = $get('jenis');
                                if ($jenis !== 'emas')
                                    return true;
                                $rekeningId = $get('rekening_id');
                                if (!$rekeningId)
                                    return false;
                                $rekening = Rekening::find($rekeningId);
                                if (!$rekening)
                                    return false;
                                if ($rekening->status_pegadaian)
                                    return true;
                                return (bool) $get('is_new_pegadaian_registration');
                            })
                            ->validationMessages(['min' => 'Jumlah penarikan minimal Rp 10.000', 'required' => 'Jumlah penarikan wajib diisi', 'max' => 'Jumlah penarikan tidak boleh melebihi saldo yang tersedia'])
                            ->helperText(fn(Get $get) => 'Saldo tersedia: ' . ($get('formatted_balance') ?? '-')),

                        Textarea::make('catatan')->label('Catatan Nasabah')->rows(3)->placeholder('Catatan tambahan untuk penarikan saldo ini')
                            ->visible(function (Get $get): bool {
                                $jenis = $get('jenis');
                                if ($jenis !== 'emas')
                                    return true;
                                $rekeningId = $get('rekening_id');
                                if (!$rekeningId)
                                    return false;
                                $rekening = Rekening::find($rekeningId);
                                if (!$rekening)
                                    return false;
                                if ($rekening->status_pegadaian)
                                    return true;
                                return (bool) $get('is_new_pegadaian_registration');
                            }),
                    ])->columns(1),

                Hidden::make('user_id')->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Waktu Penarikan')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('rekening.nama')->label('Nasabah')->searchable()->sortable(),
                TextColumn::make('amount')->label('Jumlah')->money('IDR')->sortable(),
                TextColumn::make('jenis')->label('Metode')->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawRequests::route('/'),
            'create' => Pages\CreateWithdrawRequest::route('/create'),
            'edit' => Pages\EditWithdrawRequest::route('/{record}/edit'),
        ];
    }
}

