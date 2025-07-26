<?php

namespace App\Filament\Resources\DetailSetorSampahResource\Pages;

use App\Filament\Resources\DetailSetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailSetorSampahs extends ListRecords
{
    protected static string $resource = DetailSetorSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
