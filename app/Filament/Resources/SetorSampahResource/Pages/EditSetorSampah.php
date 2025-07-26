<?php

namespace App\Filament\Resources\SetorSampahResource\Pages;

use App\Filament\Resources\SetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetorSampah extends EditRecord
{
    protected static string $resource = SetorSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
