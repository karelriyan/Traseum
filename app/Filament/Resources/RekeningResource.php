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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Pages\Actions;
use App\Filament\Resources\RekeningResource\Pages;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Support\RawJs;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
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

            Section::make('Informasi Tabungan Emas Pegadaian')
                ->schema([
                    Toggle::make('status_pegadaian')
                        ->label('Memiliki Tabungan Emas Pegadaian?')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live(),
                    TextInput::make('no_rek_pegadaian')
                        ->label('Nomor Rekening Pegadaian')
                        ->numeric()
                        ->maxLength(16)
                        ->unique(ignoreRecord: true)
                        ->visible(fn(Get $get) => $get('status_pegadaian'))
                        ->nullable()
                        ->validationMessages([
                            'required' => 'Nomor rekening pegadaian wajib diisi jika memiliki tabungan emas.',
                            'unique' => 'Nomor rekening pegadaian sudah terdaftar.',
                        ]),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['user']);
            })
            ->columns([
                TextColumn::make('no_rekening')->label('No. Rekening')->sortable()->searchable(),
                TextColumn::make('nama')->label('Nama Nasabah')->sortable()->searchable(),
                TextColumn::make('current_balance')->label('Saldo')->sortable()->money('IDR'),
                IconColumn::make('status_pegadaian')
                    ->label('Tab. Emas')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                
                // Kolom untuk export dalam urutan yang benar
                TextColumn::make('nik')->label('NIK')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_kk')->label('No. KK')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender')->label('Jenis Kelamin')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pendidikan')->label('Pendidikan')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dusun')->label('Dusun')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rw')->label('RW')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rt')->label('RT')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('telepon')->label('No. Telepon')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('points_balance')->label('Poin')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_rek_pegadaian')
                    ->label('No. Rek. Pegadaian')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                // Kolom administrasi di paling akhir
                TextColumn::make('user.name')->label('Pembuat Rekening')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Waktu Dibuat')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->fileName('rekening_nasabah_lengkap')
                    ->defaultFormat('xlsx')
                    ->disableAdditionalColumns()
                    ->filterColumnsFieldLabel('Pilih Kolom untuk Export')
                    ->fileNameFieldLabel('Nama File')
                    ->formatFieldLabel('Format File')
                    ->withColumns([
                        TextColumn::make('no_rekening')->label('No. Rekening'),
                        TextColumn::make('nama')->label('Nama Nasabah'),
                        TextColumn::make('current_balance')->label('Saldo'),
                        IconColumn::make('status_pegadaian')->label('Tab. Emas'),
                        TextColumn::make('nik')->label('NIK'),
                        TextColumn::make('no_kk')->label('No. KK'),
                        TextColumn::make('gender')->label('Jenis Kelamin'),
                        TextColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
                        TextColumn::make('pendidikan')->label('Pendidikan'),
                        TextColumn::make('dusun')->label('Dusun'),
                        TextColumn::make('rw')->label('RW'),
                        TextColumn::make('rt')->label('RT'),
                        TextColumn::make('telepon')->label('No. Telepon'),
                        TextColumn::make('points_balance')->label('Poin'),
                        TextColumn::make('no_rek_pegadaian')->label('No. Rek. Pegadaian'),
                        TextColumn::make('user.name')->label('Pembuat Rekening'),
                        TextColumn::make('created_at')->label('Waktu Dibuat'),
                        TextColumn::make('updated_at')->label('Terakhir Diubah'),
                    ])
                    ->formatStates([
                        'no_rekening' => fn ($record) => ' ' . $record->no_rekening, // Space prefix untuk Excel
                        'nama' => fn ($record) => $record->nama,
                        'current_balance' => fn ($record) => 'Rp ' . number_format($record->current_balance ?? 0, 0, ',', '.'),
                        'status_pegadaian' => fn ($record) => $record->status_pegadaian == 1 ? 'Ada' : 'Tidak Ada',
                        'nik' => fn ($record) => ' ' . $record->nik, // Space prefix untuk Excel
                        'no_kk' => fn ($record) => ' ' . $record->no_kk, // Space prefix untuk Excel
                        'gender' => fn ($record) => $record->gender,
                        'tanggal_lahir' => fn ($record) => $record->tanggal_lahir ? date('d/m/Y', strtotime($record->tanggal_lahir)) : '',
                        'pendidikan' => fn ($record) => $record->pendidikan,
                        'dusun' => fn ($record) => $record->dusun,
                        'rw' => fn ($record) => $record->rw,
                        'rt' => fn ($record) => $record->rt,
                        'telepon' => fn ($record) => $record->telepon ? ' ' . $record->telepon : '',
                        'points_balance' => fn ($record) => number_format($record->points_balance ?? 0, 0, ',', '.'),
                        'no_rek_pegadaian' => fn ($record) => $record->status_pegadaian == 1 && $record->no_rek_pegadaian ? ' ' . $record->no_rek_pegadaian : '', // Hanya tampil jika ada tabungan emas
                        'user.name' => fn ($record) => $record->user->name ?? '',
                        'created_at' => fn ($record) => $record->created_at ? date('d/m/Y H:i', strtotime($record->created_at)) : '',
                        'updated_at' => fn ($record) => $record->updated_at ? date('d/m/Y H:i', strtotime($record->updated_at)) : '',
                    ])
                    
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary'),
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
                FilamentExportBulkAction::make('export')
                    ->fileName('rekening_nasabah_selected')
                    ->defaultFormat('xlsx')
                    ->disableAdditionalColumns()
                    ->filterColumnsFieldLabel('Pilih Kolom untuk Export')
                    ->fileNameFieldLabel('Nama File')
                    ->formatFieldLabel('Format File')
                    ->withColumns([
                        TextColumn::make('no_rekening')->label('No. Rekening'),
                        TextColumn::make('nama')->label('Nama Nasabah'),
                        TextColumn::make('current_balance')->label('Saldo'),
                        IconColumn::make('status_pegadaian')->label('Tab. Emas'),
                        TextColumn::make('nik')->label('NIK'),
                        TextColumn::make('no_kk')->label('No. KK'),
                        TextColumn::make('gender')->label('Jenis Kelamin'),
                        TextColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
                        TextColumn::make('pendidikan')->label('Pendidikan'),
                        TextColumn::make('dusun')->label('Dusun'),
                        TextColumn::make('rw')->label('RW'),
                        TextColumn::make('rt')->label('RT'),
                        TextColumn::make('telepon')->label('No. Telepon'),
                        TextColumn::make('points_balance')->label('Poin'),
                        TextColumn::make('no_rek_pegadaian')->label('No. Rek. Pegadaian'),
                        TextColumn::make('user.name')->label('Pembuat Rekening'),
                        TextColumn::make('created_at')->label('Waktu Dibuat'),
                        TextColumn::make('updated_at')->label('Terakhir Diubah'),
                    ])
                    ->formatStates([
                        'no_rekening' => fn ($record) => ' ' . $record->no_rekening, // Space prefix untuk Excel
                        'nama' => fn ($record) => $record->nama,
                        'current_balance' => fn ($record) => 'Rp ' . number_format($record->current_balance ?? 0, 0, ',', '.'),
                        'status_pegadaian' => fn ($record) => $record->status_pegadaian == 1 ? 'Ada' : 'Tidak Ada',
                        'nik' => fn ($record) => ' ' . $record->nik, // Space prefix untuk Excel
                        'no_kk' => fn ($record) => ' ' . $record->no_kk, // Space prefix untuk Excel
                        'gender' => fn ($record) => $record->gender,
                        'tanggal_lahir' => fn ($record) => $record->tanggal_lahir ? date('d/m/Y', strtotime($record->tanggal_lahir)) : '',
                        'pendidikan' => fn ($record) => $record->pendidikan,
                        'dusun' => fn ($record) => $record->dusun,
                        'rw' => fn ($record) => $record->rw,
                        'rt' => fn ($record) => $record->rt,
                        'telepon' => fn ($record) => $record->telepon ? ' ' . $record->telepon : '',
                        'points_balance' => fn ($record) => number_format($record->points_balance ?? 0, 0, ',', '.'),
                        'no_rek_pegadaian' => fn ($record) => $record->status_pegadaian == 1 && $record->no_rek_pegadaian ? ' ' . $record->no_rek_pegadaian : '', // Hanya tampil jika ada tabungan emas
                        'user.name' => fn ($record) => $record->user->name ?? '',
                        'created_at' => fn ($record) => $record->created_at ? date('d/m/Y H:i', strtotime($record->created_at)) : '',
                        'updated_at' => fn ($record) => $record->updated_at ? date('d/m/Y H:i', strtotime($record->updated_at)) : '',
                    ]),
                Tables\Actions\DeleteBulkAction::make(),
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
            ->where('no_rekening', '!=', '00000000')
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
