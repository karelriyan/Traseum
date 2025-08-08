<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawRequestResource\Pages;
use App\Models\Rekening;
use App\Models\WithdrawRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Support\RawJs;

class WithdrawRequestResource extends Resource
{
    protected static ?string $model = WithdrawRequest::class;

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
                                    ->select('id', 'nama', 'nik')
                                    ->get()
                                    ->mapWithKeys(fn($rekening) => [$rekening->id => "{$rekening->nama} - {$rekening->nik}"]);
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $rekening = Rekening::find($state);
                                    if ($rekening) {
                                        $set('current_balance', $rekening->current_balance);
                                        $set('formatted_balance', $rekening->formatted_balance);
                                    }
                                }
                            })
                            ->validationMessages([
                                'required' => 'Rekening nasabah wajib dipilih.',
                            ]),

                        Forms\Components\Placeholder::make('saldo_info')
                            ->label('Saldo Tersedia')
                            ->content(function (Forms\Get $get) {
                                $rekeningId = $get('rekening_id');
                                if ($rekeningId) {
                                    $rekening = Rekening::find($rekeningId);
                                    return $rekening ? $rekening->formatted_balance : '-';
                                }
                                return '-';
                            }),

                        Forms\Components\Hidden::make('current_balance'),
                        Forms\Components\Hidden::make('formatted_balance'),
                    ]),
                Section::make('Detail Penarikan')
                    ->schema([
                        TextInput::make('amount')
                            ->label('Jumlah Penarikan')
                            ->numeric()
                            ->required()
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->minValue(10000)
                            ->maxValue(function (Forms\Get $get) {
                                return $get('current_balance') ?? 0;
                            })
                            ->validationMessages([
                                'min' => 'Jumlah penarikan minimal Rp 10.000',
                                'required' => 'Jumlah penarikan wajib diisi',
                                'max' => 'Jumlah penarikan tidak boleh melebihi saldo yang tersedia',
                            ])
                            ->helperText(function (Forms\Get $get) {
                                $balance = $get('current_balance');
                                return $balance ? 'Saldo tersedia: Rp ' . number_format($balance, 0, ',', '.') : '';
                            }),

                        Select::make('jenis')
                            ->label('Metode Penarikan')
                            ->required()
                            ->dehydrated(true)
                            ->options([
                                "Cash" => "Cash",
                                "Transfer" => "Transfer",
                            ]),

                        Textarea::make('catatan')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->placeholder('Catatan tambahan untuk permintaan ini'),
                    ])
                    ->columns(2),

                Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Penarikan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('rekening.nama')
                    ->label('Nama Nasabah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('jenis')
                    ->label('Metode')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Diproses Oleh')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn($query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn($query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListWithdrawRequests::route('/'),
            'create' => Pages\CreateWithdrawRequest::route('/create'),
            'edit' => Pages\EditWithdrawRequest::route('/{record}/edit'),
        ];
    }
}
