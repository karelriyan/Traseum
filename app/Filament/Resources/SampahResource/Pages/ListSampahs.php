<?php

namespace App\Filament\Resources\SampahResource\Pages;

use App\Filament\Resources\SampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSampahs extends ListRecords
{
    protected static string $resource = SampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
