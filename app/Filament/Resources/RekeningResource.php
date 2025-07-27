<?php

namespace App\Filament\Resources;

use App\Models\Rekening;
use App\Models\KartuKeluarga;
use App\Models\Nasabah;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\RekeningResource\Pages;
use Illuminate\Support\HtmlString;

class RekeningResource extends Resource
{
    protected static ?string $model = Rekening::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kartu Keluarga')
                    ->schema([
                        TextInput::make('kartu_keluarga_id')
                            ->relationship('kartuKeluarga', 'no_kk')
                            ->label('Nomor KK')
                            ->searchable()
                            ->reactive()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('no_kk')->minLength(16)->maxLength(16)->required()->unique(table: KartuKeluarga::class, column: 'no_kk'),
                                TextInput::make('nik_kepala_keluarga')->minLength(16)->maxLength(16)->required()->unique(table: KartuKeluarga::class, column: 'nik_kepala_keluarga'),
                                TextInput::make('nama_kepala_keluarga')->required(),
                            ])
                            ->afterStateUpdated(
                                fn($state, callable $set) => $state
                                ? ($kk = KartuKeluarga::find($state)) &&
                                $set('nama_kepala_keluarga_display', $kk?->nama_kepala_keluarga) &&
                                $set('nik_kepala_keluarga_display', $kk?->nik_kepala_keluarga) &&
                                $set('gender_kepala_keluarga_display', $kk?->gender_kepala_keluarga) &&
                                $set('telepon_kepala_keluarga_display', $kk?->telepon_kepala_keluarga)
                                : ($set('nama_kepala_keluarga_display', null) && $set('nik_kepala_keluarga_display', null))
                            ),
                    ])->columns(1),
                Section::make('Kepala Keluarga')
                    ->schema([
                        Placeholder::make('teks_kepala_keluarga')
                            ->label('')
                            ->dehydrated(false)
                            ->visible(fn($get) => (bool) $get('kartu_keluarga_id') == null)
                            ->content(function (): string|HtmlString {
                                return new HtmlString('<i>Harap Pilih/Masukkan No.KK Terlebih Dahulu</i>');
                            }),
                        TextInput::make('nik_kepala_keluarga_display')
                            ->label('NIK Kepala Keluarga')
                            ->disabled()
                            ->visible(fn($get) => (bool) $get('kartu_keluarga_id')),
                        TextInput::make('gender_kepala_keluarga_display')
                            ->label('Jenis Kelamin Kepala Keluarga')
                            ->disabled()
                            ->visible(fn($get) => (bool) $get('kartu_keluarga_id')),
                        TextInput::make('nama_kepala_keluarga_display')
                            ->label('Nama Kepala Keluarga (sesuai KTP/KK')
                            ->disabled()
                            ->visible(fn($get) => (bool) $get('kartu_keluarga_id')),
                        TextInput::make('telepon_kepala_keluarga_display')
                            ->label('No.Telp Kepala Keluarga')
                            ->disabled()
                            ->visible(fn($get) => (bool) $get('kartu_keluarga_id')),
                    ])->columns(2),
                Section::make('Anggota Keluarga (Nasabah)')
                    ->schema([
                        Repeater::make('nasabah')
                            ->label('')
                            ->schema([
                                TextInput::make('nik')->label('NIK')->minLength(16)->maxLength(16)->required()->unique(ignoreRecord: true, table: Nasabah::class, column: 'nik')
                                    ->validationMessages([
                                        'required' => 'NIK tidak boleh kosong',
                                        'min' => 'NIK harus memiliki panjang minimal 16 karakter',
                                        'max' => 'NIK harus memiliki panjang maksimal 16 karakter',
                                        'unique' => 'NIK sudah digunakan ',
                                    ]),
                                Select::make('gender')->label('Jenis Kelamin')->options(['Laki‑laki' => 'Laki‑laki', 'Perempuan' => 'Perempuan'])->required()
                                    ->validationMessages([
                                        'required' => 'Jenis Kelamin tidak boleh kosong',
                                    ]),
                                TextInput::make('nama')->required()->label('Nama Lengkap (sesuai KTP)')
                                    ->validationMessages([
                                        'required' => 'Nama tidak boleh kosong',
                                    ]),
                                TextInput::make('telepon')->tel()->nullable()->label('No.Telp'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->createItemButtonLabel('Tambah Nasabah'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kartuKeluarga.no_kk')->label('Nomor KK')->sortable()->searchable(),
                TextColumn::make('balance')->label('Saldo')->sortable(),
                TextColumn::make('points_balance')->label('Saldo Poin')->sortable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime(),
                TextColumn::make('updated_at')->label('Diubah')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekenings::route('/'),
            'create' => Pages\CreateRekening::route('/create'),
            'edit' => Pages\EditRekening::route('/{record}/edit'),
        ];
    }
}
