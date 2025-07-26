<?php

namespace App\Filament\Resources\PostinganUmkmResource\Pages;

use App\Filament\Resources\PostinganUmkmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostinganUmkms extends ListRecords
{
    protected static string $resource = PostinganUmkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
