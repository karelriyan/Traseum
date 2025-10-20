<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemasukanResource\Pages;
use App\Filament\Resources\PemasukanResource\RelationManagers;
use App\Models\Pemasukan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Rekening;
use Hexters\HexaLite\HasHexaLite;

class PemasukanResource extends Resource
{
    use HasHexaLite;
    protected static ?string $model = Pemasukan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Keuangan Bank Sampah';

    protected static ?int $navigationSort = 1;

    public $hexaSort = 8;

    public function defineGates(){
        return [
            'pemasukan.index' => __('Lihat Pemasukan'),
            'pemasukan.create' => __('Buat Pemasukan Baru'),
            'pemasukan.update' => __('Ubah Pemasukan'),
            'pemasukan.delete' => __('Hapus Pemasukan'),
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Pemasukan')
                    ->required()
                    ->default(now())
                    ->validationMessages(['required' => 'Tanggal pemasukan wajib diisi.']),
                Forms\Components\Select::make('sumber_pemasukan_id')
                    ->label('Sumber Pemasukan')
                    ->relationship('sumberPemasukan', 'nama_pemasukan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_pemasukan')
                            ->label('Nama Sumber Pemasukan')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Pemasukan')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
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
                    ->label('Bukti Pemasukan (Foto/Document)')
                    ->image()
                    ->maxSize(20)
                    ->directory('bukti_pemasukan')
                    ->columnSpanFull()
                    ->nullable(),

                Forms\Components\Hidden::make('rekening_id')
                    ->default(fn() => Rekening::where('no_rekening', '00000000')->value('id')),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('sumberPemasukan.nama_pemasukan')->label('Sumber Pemasukan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal')->money('IDR')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('metode_pembayaran')->label('Metode Pembayaran')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pencatat')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat Pada')->dateTime()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diubah Pada')->dateTime()->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->label('Tanggal Pemasukan')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date))
                            ->when($data['to'], fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date));
                    }),
                Tables\Filters\Filter::make('sumber_pemasukan_id')
                    ->label('Sumber Pemasukan')
                    ->form([
                        Forms\Components\Select::make('sumber_pemasukan_id')->relationship('sumberPemasukan', 'nama_pemasukan')->preload()->searchable(),
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->visible(fn() => hexa()->can('pemasukan.update')),
                Tables\Actions\DeleteAction::make()
                ->visible(fn() => hexa()->can('pemasukan.delete')),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn() => hexa()->can('pemasukan.delete')),
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
            'index' => Pages\ListPemasukans::route('/'),
            'create' => Pages\CreatePemasukan::route('/create'),
            'edit' => Pages\EditPemasukan::route('/{record}/edit'),
        ];
    }
}
