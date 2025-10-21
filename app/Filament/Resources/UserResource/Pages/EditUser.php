<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
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
        ];
    }

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('user.update');
    }

    protected function afterSave(): void
    {
        // Ensure cached roles/permissions/panel access are refreshed after edits (including role changes)
        $this->record->refreshCache();
    }
}
