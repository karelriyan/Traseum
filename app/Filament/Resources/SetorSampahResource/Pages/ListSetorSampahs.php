<?php

namespace App\Filament\Resources\SetorSampahResource\Pages;

use App\Filament\Resources\SetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSetorSampahs extends ListRecords
{
    protected static string $resource = SetorSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn() => hexa()->can('setor_sampah.create')),
        ];
    }
}
