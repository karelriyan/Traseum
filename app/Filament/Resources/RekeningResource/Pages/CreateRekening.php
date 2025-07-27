<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\KartuKeluarga;
use App\Models\Nasabah;

class CreateRekening extends CreateRecord
{
    protected static string $resource = RekeningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $nasabahData = $data['nasabah'] ?? [];
        unset($data['nasabah']);

        // Store nasabah data temporarily to be processed after Rekening is created
        $this->nasabahToCreate = $nasabahData;

        return $data;
    }

    protected function afterCreate(): void
    {
        $kartuKeluarga = KartuKeluarga::find($this->record->kartu_keluarga_id);

        if ($kartuKeluarga && isset($this->nasabahToCreate)) {
            foreach ($this->nasabahToCreate as $nasabahItem) {
                $kartuKeluarga->nasabah()->create($nasabahItem);
            }
        }
    }
}