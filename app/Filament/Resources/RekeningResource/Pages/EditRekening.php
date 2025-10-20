<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Nasabah;
use Filament\Notifications\Notification;
use Filament\Support\Facades\Dialog;
use App\Models\Rekening;

class EditRekening extends EditRecord
{
    protected static string $resource = RekeningResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('rekening.update');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn() => hexa()->can('rekening.delete')),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // This logic is identical to afterCreate()
        if (!$this->record->status_lengkap) {
            Notification::make()
                ->title('Peringatan Data Belum Lengkap')
                ->body('Perubahan berhasil disimpan, namun data nasabah ini belum lengkap.')
                ->warning()
                ->persistent()
                ->send();
        }
    }
}
