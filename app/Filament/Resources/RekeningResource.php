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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
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
                        ->required(fn(Get $get) => $get('status_pegadaian'))
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
                TextColumn::make('user.name')->label('Pembuat Rekening')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Waktu Dibuat')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make('export_semua')
                            ->label('Export Semua Data')
                            ->fromTable()
                            ->withFilename(fn () => 'seluruh_rekening_nasabah_' . date('Y-m-d_H-i-s')),
                        
                        ExcelExport::make('export_custom_header')
                            ->label('Export Custom')
                            ->askForWriterType()
                            ->askForFilename()
                            ->form([
                                CheckboxList::make('columns')
                                    ->label('Pilih Kolom yang Akan Di-Export')
                                    ->options([
                                        'no_rekening' => 'No. Rekening',
                                        'nama' => 'Nama Nasabah',
                                        'nik' => 'NIK',
                                        'no_kk' => 'No. KK',
                                        'gender' => 'Jenis Kelamin',
                                        'tanggal_lahir' => 'Tanggal Lahir',
                                        'pendidikan' => 'Pendidikan',
                                        'alamat' => 'Alamat (Dusun/RW/RT)',
                                        'telepon' => 'No. Telepon',
                                        'current_balance' => 'Saldo Saat Ini',
                                        'points_balance' => 'Poin',
                                        'status_pegadaian' => 'Tab. Emas Pegadaian',
                                        'no_rek_pegadaian' => 'No. Rek. Pegadaian',
                                        'user.name' => 'Pembuat Rekening',
                                        'created_at' => 'Tanggal Dibuat',
                                        'updated_at' => 'Terakhir Diubah',
                                    ])
                                    ->columns(2)
                                    ->required()
                                    ->default([
                                        'no_rekening',
                                        'nama',
                                        'current_balance',
                                        'points_balance'
                                    ])
                                    ->helperText('Pilih kolom yang ingin dimasukkan dalam file export'),
                            ])
                            ->withColumns(function ($data) {
                                $selectedColumns = $data['columns'] ?? [];
                                $columns = [];
                                
                                foreach ($selectedColumns as $column) {
                                    switch ($column) {
                                        case 'no_rekening':
                                            $columns[] = Column::make('no_rekening')->heading('No. Rekening');
                                            break;
                                        case 'nama':
                                            $columns[] = Column::make('nama')->heading('Nama Nasabah');
                                            break;
                                        case 'nik':
                                            $columns[] = Column::make('nik')->heading('NIK');
                                            break;
                                        case 'no_kk':
                                            $columns[] = Column::make('no_kk')->heading('No. KK');
                                            break;
                                        case 'gender':
                                            $columns[] = Column::make('gender')->heading('Jenis Kelamin');
                                            break;
                                        case 'tanggal_lahir':
                                            $columns[] = Column::make('tanggal_lahir')
                                                ->heading('Tanggal Lahir')
                                                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y', strtotime($state)) : '');
                                            break;
                                        case 'pendidikan':
                                            $columns[] = Column::make('pendidikan')->heading('Pendidikan');
                                            break;
                                        case 'alamat':
                                            $columns[] = Column::make('alamat')
                                                ->heading('Alamat')
                                                ->formatStateUsing(fn ($record) => "Dusun {$record->dusun}, RW {$record->rw}, RT {$record->rt}");
                                            break;
                                        case 'telepon':
                                            $columns[] = Column::make('telepon')->heading('No. Telepon');
                                            break;
                                        case 'current_balance':
                                            $columns[] = Column::make('current_balance')
                                                ->heading('Saldo (Rp)')
                                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'));
                                            break;
                                        case 'points_balance':
                                            $columns[] = Column::make('points_balance')
                                                ->heading('Poin')
                                                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'));
                                            break;
                                        case 'status_pegadaian':
                                            $columns[] = Column::make('status_pegadaian')
                                                ->heading('Tab. Emas')
                                                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak');
                                            break;
                                        case 'no_rek_pegadaian':
                                            $columns[] = Column::make('no_rek_pegadaian')->heading('No. Rek. Pegadaian');
                                            break;
                                        case 'user.name':
                                            $columns[] = Column::make('user.name')->heading('Pembuat Rekening');
                                            break;
                                        case 'created_at':
                                            $columns[] = Column::make('created_at')
                                                ->heading('Tanggal Dibuat')
                                                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : '');
                                            break;
                                        case 'updated_at':
                                            $columns[] = Column::make('updated_at')
                                                ->heading('Terakhir Diubah')
                                                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : '');
                                            break;
                                    }
                                }
                                
                                return $columns;
                            })
                            ->withFilename(function ($filename, $data) {
                                if (empty($filename)) {
                                    $selectedCount = count($data['columns'] ?? []);
                                    return "rekening_nasabah_custom_{$selectedCount}_kolom_" . date('Y-m-d_H-i-s');
                                }
                                return $filename . '_' . date('Y-m-d_H-i-s');
                            }),
                    ])
                    ->label('Export Data')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success'),
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
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make('export_semua')
                            ->label('Export Semua Kolom')
                            ->fromTable()
                            ->withFilename(fn () => 'rekening_nasabah_lengkap_' . date('Y-m-d_H-i-s')),
                        
                        ExcelExport::make('export_custom')
                            ->label('Export Custom')
                            ->askForWriterType()
                            ->askForFilename()
                            ->form([
                                CheckboxList::make('columns')
                                    ->label('Pilih Kolom yang Akan Di-Export')
                                    ->options([
                                        'nama' => 'Nama Nasabah',
                                        'nik' => 'NIK',
                                        'no_kk' => 'No. KK',
                                        'gender' => 'Jenis Kelamin',
                                        'tanggal_lahir' => 'Tanggal Lahir',
                                        'pendidikan' => 'Pendidikan',
                                        'alamat' => 'Alamat (Dusun/RW/RT)',
                                        'telepon' => 'No. Telepon',
                                        'current_balance' => 'Saldo Saat Ini',
                                        'points_balance' => 'Poin',
                                        'user.name' => 'Pembuat Rekening',
                                        'created_at' => 'Tanggal Dibuat',
                                        'updated_at' => 'Terakhir Diubah',
                                    ])
                                    ->columns(2)
                                    ->required()
                                    ->default([
                                        'nama',
                                        'nik', 
                                        'no_kk',
                                        'current_balance',
                                        'points_balance'
                                    ])
                                    ->helperText('Pilih kolom yang ingin dimasukkan dalam file export'),
                            ])
                            ->withColumns(function ($data) {
                                $selectedColumns = $data['columns'] ?? [];
                                $columns = [];
                                
                                if (in_array('nama', $selectedColumns)) {
                                    $columns[] = Column::make('nama')->heading('Nama Nasabah');
                                }
                                if (in_array('nik', $selectedColumns)) {
                                    $columns[] = Column::make('nik')->heading('NIK');
                                }
                                if (in_array('no_kk', $selectedColumns)) {
                                    $columns[] = Column::make('no_kk')->heading('No. KK');
                                }
                                if (in_array('gender', $selectedColumns)) {
                                    $columns[] = Column::make('gender')->heading('Jenis Kelamin');
                                }
                                if (in_array('tanggal_lahir', $selectedColumns)) {
                                    $columns[] = Column::make('tanggal_lahir')
                                        ->heading('Tanggal Lahir')
                                        ->formatStateUsing(fn ($state) => $state ? date('d/m/Y', strtotime($state)) : '');
                                }
                                if (in_array('pendidikan', $selectedColumns)) {
                                    $columns[] = Column::make('pendidikan')->heading('Pendidikan');
                                }
                                if (in_array('alamat', $selectedColumns)) {
                                    $columns[] = Column::make('alamat')
                                        ->heading('Alamat')
                                        ->formatStateUsing(fn ($record) => "Dusun {$record->dusun}, RW {$record->rw}, RT {$record->rt}");
                                }
                                if (in_array('telepon', $selectedColumns)) {
                                    $columns[] = Column::make('telepon')->heading('No. Telepon');
                                }
                                if (in_array('current_balance', $selectedColumns)) {
                                    $columns[] = Column::make('current_balance')
                                        ->heading('Saldo (Rp)')
                                        ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'));
                                }
                                if (in_array('points_balance', $selectedColumns)) {
                                    $columns[] = Column::make('points_balance')
                                        ->heading('Poin')
                                        ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'));
                                }
                                if (in_array('user.name', $selectedColumns)) {
                                    $columns[] = Column::make('user.name')->heading('Pembuat Rekening');
                                }
                                if (in_array('created_at', $selectedColumns)) {
                                    $columns[] = Column::make('created_at')
                                        ->heading('Tanggal Dibuat')
                                        ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : '');
                                }
                                if (in_array('updated_at', $selectedColumns)) {
                                    $columns[] = Column::make('updated_at')
                                        ->heading('Terakhir Diubah')
                                        ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i', strtotime($state)) : '');
                                }
                                
                                return $columns;
                            })
                            ->withFilename(function ($filename, $data) {
                                if (empty($filename)) {
                                    $selectedCount = count($data['columns'] ?? []);
                                    return "rekening_nasabah_custom_{$selectedCount}_kolom_" . date('Y-m-d_H-i-s');
                                }
                                return $filename . '_' . date('Y-m-d_H-i-s');
                            }),
                            
                        ExcelExport::make('export_ringkas')
                            ->label('Export Ringkas (Nama, NIK, Saldo)')
                            ->withColumns([
                                Column::make('nama')->heading('Nama Nasabah'),
                                Column::make('nik')->heading('NIK'),
                                Column::make('no_kk')->heading('No. KK'),
                                Column::make('current_balance')
                                    ->heading('Saldo (Rp)')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                Column::make('points_balance')
                                    ->heading('Poin')
                                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                            ])
                            ->withFilename(fn () => 'rekening_nasabah_ringkas_' . date('Y-m-d_H-i-s')),
                    ]),
                Tables\Actions\BulkAction::make('bulkChangePassword')
                    ->label('Ubah Password Massal')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->form([
                        TextInput::make('new_password')
                            ->label('Password Baru (untuk semua yang dipilih)')
                            ->password()
                            ->required()
                            ->minLength(6)
                            ->maxLength(255)
                            ->helperText('Password ini akan diterapkan ke semua nasabah yang dipilih'),
                        TextInput::make('confirm_password')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->required()
                            ->same('new_password'),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                        $successCount = 0;
                        $errorCount = 0;
                        
                        foreach ($records as $record) {
                            if ($record->user) {
                                $record->user->update([
                                    'password' => Hash::make($data['new_password'])
                                ]);
                                $successCount++;
                            } else {
                                $errorCount++;
                            }
                        }
                        
                        if ($successCount > 0) {
                            Notification::make()
                                ->title('Password berhasil diubah')
                                ->body("Password {$successCount} nasabah telah diperbarui." . 
                                      ($errorCount > 0 ? " {$errorCount} gagal diupdate." : ""))
                                ->success()
                                ->send();
                        }
                        
                        if ($errorCount > 0 && $successCount === 0) {
                            Notification::make()
                                ->title('Error')
                                ->body("Gagal mengubah password {$errorCount} nasabah.")
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Ubah Password Massal')
                    ->modalDescription('Password yang sama akan diterapkan ke semua nasabah yang dipilih.')
                    ->modalSubmitActionLabel('Ubah Semua Password')
                    ->deselectRecordsAfterCompletion(),
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
