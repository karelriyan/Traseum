<?php

namespace App\Filament\Resources;

use App\Models\Rekening;
use App\Models\KartuKeluarga;
use App\Models\Nasabah;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\RekeningResource\Pages;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DeleteAction;

class RekeningResource extends Resource
{
    protected static ?string $model = Rekening::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Manajemen Pengguna & Keuangan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Kartu Keluarga')
                ->schema([
                    Select::make('kartu_keluarga_id')
                        ->relationship('kartuKeluarga', 'no_kk')
                        ->label('Nomor KK')
                        ->placeholder('Pilih atau buat KK baru')
                        ->searchable()
                        ->reactive()
                        ->createOptionForm([
                            TextInput::make('no_kk')
                                ->label('Nomor KK')
                                ->minLength(16)
                                ->maxLength(16)
                                ->required()
                                ->unique(table: KartuKeluarga::class, column: 'no_kk'),
                            TextInput::make('nik_kepala_keluarga')
                                ->label('NIK Kepala')
                                ->minLength(16)
                                ->maxLength(16)
                                ->required()
                                ->unique(table: KartuKeluarga::class, column: 'nik_kepala_keluarga'),
                            TextInput::make('nama_kepala_keluarga')
                                ->label('Nama Kepala Keluarga')
                                ->required(),
                            Select::make('gender_kepala_keluarga')
                                ->label('Jenis Kelamin Kepala Keluarga')
                        ])
                        ->afterStateUpdated(function ($state, callable $set) {
                            fn($state, callable $set) => $state
                                ? ($kk = KartuKeluarga::find($state)) &&
                                $set('nama_kepala_keluarga_display', $kk?->nama_kepala_keluarga) &&
                                $set('nik_kepala_keluarga_display', $kk?->nik_kepala_keluarga) &&
                                $set('gender_kepala_keluarga_display', $kk?->gender_kepala_keluarga) &&
                                $set('telepon_kepala_keluarga_display', $kk?->telepon_kepala_keluarga)
                                : ($set('nama_kepala_keluarga_display', null) && $set('nik_kepala_keluarga_display', null));
                        }),
                    TextInput::make('nama_kepala_keluarga_display')
                        ->label('Nama Kepala Keluarga')
                        ->disabled()
                        ->visible(fn($get) => (bool) $get('kartu_keluarga_id')),
                    TextInput::make('nik_kepala_keluarga_display')
                        ->label('NIK Kepala Keluarga')
                        ->disabled()
                        ->visible(fn($get) => (bool) $get('kartu_keluarga_id')),
                ]),
            Section::make('Anggota Keluarga (Nasabah)')
                ->schema([
                    Repeater::make('nasabah')
                        ->schema([
                            TextInput::make('nik')
                                ->label('NIK')
                                ->minLength(16)
                                ->maxLength(16)
                                ->required()
                                ->unique(ignoreRecord: true, table: Nasabah::class, column: 'nik'),
                            TextInput::make('nama_lengkap_sesuai_ktp')
                                ->label('Nama Lengkap')
                                ->required(),
                            Select::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options(['Laki‑laki' => 'Laki‑laki', 'Perempuan' => 'Perempuan'])
                                ->required(),
                            TextInput::make('telepon')
                                ->label('Telepon (Opsional)')
                                ->tel()
                                ->nullable(),
                        ])
                        ->columns(1)
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
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
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
