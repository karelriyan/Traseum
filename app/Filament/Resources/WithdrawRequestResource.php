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
                            ->live()
                            ->dehydrated(true)
                            ->options([
                                "Cash" => "Cash",
                                "Transfer" => "Transfer",
                            ]),

                        TextInput::make('bank_name')
                            ->label('Nama Bank')
                            ->visible(fn(Forms\Get $get) => $get('jenis') === 'Transfer')
                            ->required(fn(Forms\Get $get) => $get('jenis') === 'Transfer'),

                        TextInput::make('account_number')
                            ->label('Nomor Rekening')
                            ->visible(fn(Forms\Get $get) => $get('jenis') === 'Transfer')
                            ->required(fn(Forms\Get $get) => $get('jenis') === 'Transfer'),

                        TextInput::make('account_holder_name')
                            ->label('Nama Pemilik Rekening')
                            ->visible(fn(Forms\Get $get) => $get('jenis') === 'Transfer')
                            ->required(fn(Forms\Get $get) => $get('jenis') === 'Transfer'),

                        Textarea::make('catatan')
                            ->label('Catatan Nasabah')
                            ->rows(3)
                            ->placeholder('Catatan tambahan untuk penarikan saldo ini'),
                    ])
                    ->columns(2),

                Hidden::make('user_id')
                    ->default(auth()->id()),

                Hidden::make('status')
                    ->default('pending'),
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
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('jenis')
                    ->label('Metode')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'processed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'processed' => 'Diproses',
                        default => ucfirst($state),
                    }),

                TextColumn::make('user.name')
                    ->label('Diproses Oleh')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'processed' => 'Diproses',
                    ]),

                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Metode')
                    ->options([
                        'Cash' => 'Cash',
                        'Transfer' => 'Transfer',
                    ]),

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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(WithdrawRequest $record): bool => $record->status === 'pending'),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn(WithdrawRequest $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Penarikan Saldo')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui penarikan saldo ini?')
                    ->form([
                        Textarea::make('notes')
                            ->label('Catatan Admin (opsional)')
                            ->rows(3),
                    ])
                    ->action(function (WithdrawRequest $record, array $data): void {
                        $record->update([
                            'status' => 'approved',
                            'processed_by' => auth()->id(),
                            'processed_at' => now(),
                            'notes' => $data['notes'] ?? null,
                        ]);
                        
                        // Tambahkan notifikasi
                        \Filament\Notifications\Notification::make()
                            ->title('Penarikan Saldo Disetujui')
                            ->body("Penarikan saldo senilai Rp " . number_format($record->amount, 0, ',', '.') . " telah disetujui.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn(WithdrawRequest $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Penarikan Saldo')
                    ->modalDescription('Apakah Anda yakin ingin menolak penarikan saldo ini?')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (WithdrawRequest $record, array $data): void {
                        // Kembalikan saldo jika ditolak
                        if ($record->rekening && $record->amount > 0) {
                            $rekening = $record->rekening;
                            $rekening->balance += $record->amount;
                            $rekening->save();

                            // Hapus transaksi saldo yang sudah dibuat
                            $record->saldoTransaction()?->delete();
                        }
                        
                        $record->update([
                            'status' => 'rejected',
                            'processed_by' => auth()->id(),
                            'processed_at' => now(),
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                        
                        // Tambahkan notifikasi
                        \Filament\Notifications\Notification::make()
                            ->title('Penarikan Saldo Ditolak')
                            ->body("Penarikan saldo senilai Rp " . number_format($record->amount, 0, ',', '.') . " telah ditolak.")
                            ->warning()
                            ->send();
                    }),

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
