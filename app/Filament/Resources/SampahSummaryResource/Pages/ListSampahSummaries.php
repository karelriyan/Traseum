<?php

namespace App\Filament\Resources\SampahSummaryResource\Pages;

use App\Filament\Resources\SampahSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSampahSummaries extends ListRecords
{
    protected static string $resource = SampahSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
