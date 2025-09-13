<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranResource\Pages;
use App\Filament\Resources\PengeluaranResource\RelationManagers;
use App\Models\Pengeluaran;
use App\Models\Rekening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Keuangan Bank Sampah';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // Ambil saldo rekening donasi/kas untuk validasi dan tampilan
        $kasBalance = Rekening::where('no_rekening', '00000000')->value('balance') ?? 0;

        return $form
            ->schema([
                // Section untuk menampilkan saldo kas yang tersedia
                Forms\Components\Section::make('Informasi Saldo Kas')
                    ->schema([
                        Forms\Components\Placeholder::make('saldo_kas')
                            ->label('Saldo Kas Bank Sampah Saat Ini')
                            ->content(function () use ($kasBalance) {
                                // Format angka menjadi format Rupiah
                                return 'Rp ' . Number::format($kasBalance, locale: 'id');
                            }),
                    ]),

                // Section untuk form input pengeluaran
                Forms\Components\Section::make('Detail Pengeluaran')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pengeluaran')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('kategori_pengeluaran_id')
                            ->label('Kategori Pengeluaran')
                            ->relationship('kategoriPengeluaran', 'nama_pengeluaran')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_pengeluaran')
                                    ->label('Nama Kategori Pengeluaran')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('nominal')
                            ->label('Nominal Pengeluaran')
                            ->prefix('Rp')
                            ->required()
                            ->numeric()
                            // Validasi: Nominal tidak boleh melebihi saldo kas
                            ->maxValue($kasBalance)
                            ->validationMessages([
                                'maxValue' => 'Nominal pengeluaran tidak boleh melebihi saldo kas yang tersedia.',
                            ]),
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'Tunai' => 'Tunai',
                                'Transfer' => 'Transfer',
                                'E-Wallet' => 'E-Wallet',
                                'Lainnya' => 'Lainnya',
                            ]),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('bukti')
                            ->label('Bukti Pengeluaran (Foto/Document)')
                            ->image()
                            // Ukuran file maksimal 2MB (2048 KB)
                            ->maxSize(2048)
                            ->directory('bukti_pengeluaran')
                            ->columnSpanFull()
                            ->nullable(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('kategoriPengeluaran.nama_pengeluaran')->label('Kategori Pengeluaran')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal')->money('IDR')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pencatat')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat Pada')->dateTime()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diubah Pada')->dateTime()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date))
                            ->when($data['to'], fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date));
                    }),
                Tables\Filters\SelectFilter::make('kategori_pengeluaran_id')
                    ->label('Kategori Pengeluaran')
                    ->relationship('kategoriPengeluaran', 'nama_pengeluaran')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengeluarans::route('/'),
            'create' => Pages\CreatePengeluaran::route('/create'),
            'edit' => Pages\EditPengeluaran::route('/{record}/edit'),
        ];
    }
}
