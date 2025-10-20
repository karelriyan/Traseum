<?php

namespace App\Filament\Resources\SetorSampahResource\Pages;

use App\Filament\Resources\SetorSampahResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSetorSampah extends CreateRecord
{
    protected static string $resource = SetorSampahResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('setor_sampah.create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validasi bahwa perhitungan sudah dilakukan
        if (!isset($data['calculation_performed']) || !$data['calculation_performed']) {
            Notification::make()
                ->title('Perhitungan Belum Dilakukan')
                ->body('Silakan tekan tombol "Hitung" terlebih dahulu sebelum menyimpan data.')
                ->danger()
                ->send();
                
            $this->halt();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
