<?php

namespace App\Filament\Resources\PemasukanResource\Pages;

use App\Filament\Resources\PemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemasukans extends ListRecords
{
    protected static string $resource = PemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
