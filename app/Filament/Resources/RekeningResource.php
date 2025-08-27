<?php

namespace App\Filament\Resources;

use App\Models\Rekening;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Pages\Actions;
use App\Filament\Resources\RekeningResource\Pages;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Support\RawJs;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables;
use App\Filament\Resources\RekeningResource\RelationManagers;

class RekeningResource extends Resource
{
    protected static ?string $model = Rekening::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationLabel = 'Rekening Nasabah';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Kartu Keluarga')
                ->schema([
                    TextInput::make('no_kk')
                        ->label('Nomor KK (Kartu Keluarga)')
                        ->length(16)
                        ->required()
                        ->rule('regex:/^\d+$/')
                        ->placeholder('Masukkan Nomor KK')
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'required' => 'No. KK tidak boleh kosong',
                            'unique' => 'No. KK sudah pernah terdaftar (cek pada file yang sudah dihapus jika tidak muncul)',
                            'regex' => 'No. KK hanya boleh berisi angka',
                        ]),
                ]),
            Section::make('Informasi Nasabah')
                ->schema([
                    TextInput::make('nik')
                        ->label('Nomor Induk Kependudukan (NIK)')
                        ->required()
                        ->length(16)
                        ->rule('regex:/^\d+$/')
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'required' => 'NIK tidak boleh kosong',
                            'unique' => 'NIK sudah pernah terdaftar (cek pada file yang sudah dihapus jika tidak muncul)',
                            'regex' => 'NIK hanya boleh berisi angka',
                        ]),
                    TextInput::make('nama')
                        ->label('Nama Lengkap (sesuai KTP)')
                        ->required()
                        ->validationMessages([
                            'required' => 'Nama tidak boleh kosong'
                        ]),
                    Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options(['Laki‑laki' => 'Laki‑laki', 'Perempuan' => 'Perempuan'])
                        ->required()
                        ->validationMessages([
                            'required' => 'Jenis Kelamin tidak boleh kosong'
                        ]),
                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir')
                        ->required()
                        ->validationMessages([
                            'required' => 'Tanggal Lahir tidak boleh kosong',
                        ]),
                    Select::make('pendidikan')
                        ->label('Pendidikan Terakhir')
                        ->options([
                            'TIDAK/BELUM SEKOLAH' => 'TIDAK/BELUM SEKOLAH',
                            'BELUM TAMAT SD/SEDERAJAT' => 'BELUM TAMAT SD/SEDERAJAT',
                            'TAMAT SD/SEDERAJAT' => 'TAMAT SD/SEDERAJAT',
                            'SLTP/SEDERAJAT' => 'SLTP/SEDERAJAT',
                            'SLTA/SEDERAJAT' => 'SLTA/SEDERAJAT',
                            'DIPLOMA I/II' => 'DIPLOMA I/II',
                            'AKADEMI/DIPLOMA III/S. MUDA' => 'AKADEMI/DIPLOMA III/S. MUDA',
                            'DIPLOMA IV/STRATA I' => 'DIPLOMA IV/STRATA I',
                            'STRATA II' => 'STRATA II',
                            'STRATA III' => 'STRATA III',
                        ])
                        ->required()
                        ->validationMessages([
                            'required' => 'Pendidikan tidak boleh kosong'
                        ]),
                ])
                ->columns(1),
            Section::make('Alamat')
                ->schema([
                    TextInput::make('dusun')
                        ->label('Dusun')
                        ->required()
                        ->length(1)
                        ->minValue(1)
                        ->numeric()
                        ->validationMessages([
                            'required' => 'Dusun tidak boleh kosong',
                            'regex' => 'Dusun hanya boleh berisi huruf dan angka',
                            'digits' => 'Dusun harus 1 digit',
                            'min_value' => 'Tidak voleh kurang dari 1',
                        ]),
                    TextInput::make('rw')
                        ->label('RW')
                        ->required()
                        ->maxLength(2)
                        ->minValue(1)
                        ->numeric()
                        ->rule('regex:/^[0-9]+$/')
                        ->validationMessages([
                            'required' => 'RW tidak boleh kosong',
                            'regex' => 'RW hanya boleh berisi angka',
                            'max_digits' => 'RW maksimal 2 digit',
                            'min_value' => 'Tidak voleh kurang dari 1',
                        ]),
                    TextInput::make('rt')
                        ->label('RT')
                        ->required()
                        ->maxLength(2)
                        ->minValue(1)
                        ->numeric()
                        ->rule('regex:/^[0-9]+$/')
                        ->validationMessages([
                            'required' => 'RT tidak boleh kosong',
                            'regex' => 'RT hanya boleh berisi angka',
                            'max_digits' => 'RT maksimal 2 digit',
                            'min_value' => 'Tidak voleh kurang dari 1',
                        ]),
                ])->columns(3),
            Section::make('Informasi Kontak')
                ->schema([
                    TextInput::make('telepon')
                        ->label('Telepon (Opsional)')
                        ->tel()
                        ->nullable()
                        ->validationMessages([
                            'tel' => 'Nomor Telepon tidak valid',
                            'regex' => 'Nomor Telepon tidak valid'
                        ]),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama Nasabah')->sortable()->searchable(),
                TextColumn::make('no_kk')->label('Nomor KK')->sortable()->searchable(),
                TextColumn::make('current_balance')->label('Saldo')->sortable()->money('IDR'),
                TextColumn::make('points_balance')->label('Poin')->sortable()->numeric()->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                TextColumn::make('user.name')->label('Pembuat Rekening')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Waktu Dibuat')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withTrashed();
    }
    public static function getRelations(): array
    {
        return [
            RelationManagers\SaldoTransactionRelationManager::class,
        ];
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
