<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use App\Filament\Resources\RekeningResource\Widgets;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekenings extends ListRecords
{
    protected static string $resource = RekeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn() => hexa()->can('rekening.create')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
                // Perbaikan: Menggunakan ::class, bukan ::make()
            Widgets\RekeningOverview::class,
        ];
    }
}
