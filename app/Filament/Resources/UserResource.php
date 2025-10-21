<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Hexters\HexaLite\HasHexaLite;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasHexaLite;

    protected static ?int $hexaSort = 1;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'Pengelolaan Admin';

    protected static ?int $navigationSort = 1;

    public function defineGates()
    {
        return [
            'user.index' => __('Lihat Pengelolaan Admin'),
            'user.create' => __('Buat Admin Baru'),
            'user.update' => __('Ubah Admin'),
            'user.delete' => __('Hapus Admin'),
        ];
    }

    public static function canAccess(): bool
    {
        return hexa()->can('user.index');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengguna')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('roles')
                            ->relationship(
                                'roles',
                                'name',
                                fn($query) => $query->where('name', '!=', 'Super Admin')
                            )
                            ->preload()
                            ->required()
                            ->validationMessages([
                                'required' => 'Jabatan wajib diisi',
                            ])
                            ->label('Jabatan'),


                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ganti Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->same('password')
                            ->maxLength(255)
                            ->required(fn(string $context): bool => $context === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Jabatan')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles.name')
                    ->label('Jabatan')
                    ->options(function () {
                        return \App\Models\Role::where('name', '!=', 'Super Admin')
                            ->pluck('name', 'name')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => hexa()->can('user.update')),
                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn($record) =>
                        hexa()->can('user.delete') &&
                        !$record->roles()->where('name', 'Super Admin')->exists()
                    )
                    ->before(function ($record, $action) {
                        if ($record->roles()->where('name', 'Super Admin')->exists()) {
                            $action->halt();
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Menghapus')
                                ->body('Akun Super Admin tidak dapat dihapus.')
                                ->danger()
                                ->send();
                        }
                    }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
