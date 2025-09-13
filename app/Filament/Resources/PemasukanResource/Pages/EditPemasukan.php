<?php

namespace App\Filament\Resources\PemasukanResource\Pages;

use App\Filament\Resources\PemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemasukan extends EditRecord
{
    protected static string $resource = PemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
