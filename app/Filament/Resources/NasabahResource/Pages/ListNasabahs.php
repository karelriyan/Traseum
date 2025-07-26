<?php

namespace App\Filament\Resources\NasabahResource\Pages;

use App\Filament\Resources\NasabahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNasabahs extends ListRecords
{
    protected static string $resource = NasabahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
