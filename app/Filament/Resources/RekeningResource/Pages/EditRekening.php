<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\KartuKeluarga;
use App\Models\Nasabah;

class EditRekening extends EditRecord
{
    protected static string $resource = RekeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Store nasabah data temporarily
        $this->nasabahToSync = $data['nasabah'] ?? [];
        unset($data['nasabah']);

        return $data;
    }

    protected function afterSave(): void
    {
        $kartuKeluarga = $this->record->kartuKeluarga; // Get the associated KartuKeluarga

        if ($kartuKeluarga && isset($this->nasabahToSync)) {
            $existingNasabahIds = $kartuKeluarga->nasabah->pluck('id')->toArray();
            $nasabahIdsInForm = [];

            foreach ($this->nasabahToSync as $nasabahItem) {
                if (isset($nasabahItem['id'])) {
                    // Update existing nasabah
                    $nasabah = Nasabah::find($nasabahItem['id']);
                    if ($nasabah) {
                        $nasabah->update($nasabahItem);
                        $nasabahIdsInForm[] = $nasabah->id;
                    }
                } else {
                    // Create new nasabah
                    $newNasabah = $kartuKeluarga->nasabah()->create($nasabahItem);
                    $nasabahIdsInForm[] = $newNasabah->id;
                }
            }

            // Delete nasabah records that are no longer in the form
            $nasabahIdsToDelete = array_diff($existingNasabahIds, $nasabahIdsInForm);
            Nasabah::whereIn('id', $nasabahIdsToDelete)->delete();
        }
    }
}