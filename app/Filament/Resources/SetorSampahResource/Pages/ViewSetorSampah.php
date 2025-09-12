<?php

namespace App\Filament\Resources\SetorSampahResource\Pages;

use App\Filament\Resources\SetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSetorSampah extends ViewRecord
{
    protected static string $resource = SetorSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}