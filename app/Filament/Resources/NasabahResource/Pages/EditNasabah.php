<?php

namespace App\Filament\Resources\NasabahResource\Pages;

use App\Filament\Resources\NasabahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNasabah extends EditRecord
{
    protected static string $resource = NasabahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
