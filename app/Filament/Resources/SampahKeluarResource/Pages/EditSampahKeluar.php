<?php

namespace App\Filament\Resources\SampahKeluarResource\Pages;

use App\Filament\Resources\SampahKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSampahKeluar extends EditRecord
{
    protected static string $resource = SampahKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
