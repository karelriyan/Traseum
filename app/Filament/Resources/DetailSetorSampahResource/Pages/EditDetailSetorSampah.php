<?php

namespace App\Filament\Resources\DetailSetorSampahResource\Pages;

use App\Filament\Resources\DetailSetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailSetorSampah extends EditRecord
{
    protected static string $resource = DetailSetorSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
