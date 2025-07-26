<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekening extends EditRecord
{
    protected static string $resource = RekeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
