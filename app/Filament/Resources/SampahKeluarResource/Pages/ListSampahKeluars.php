<?php

namespace App\Filament\Resources\SampahKeluarResource\Pages;

use App\Filament\Resources\SampahKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSampahKeluars extends ListRecords
{
    protected static string $resource = SampahKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->visible(fn() => hexa()->can('sampah_keluar.create')),
        ];
    }
}
