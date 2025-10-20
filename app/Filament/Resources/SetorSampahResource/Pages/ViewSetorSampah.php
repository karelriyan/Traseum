<?php

namespace App\Filament\Resources\SetorSampahResource\Pages;

use App\Filament\Resources\SetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSetorSampah extends ViewRecord
{
    protected static string $resource = SetorSampahResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('setor_sampah.view');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->visible(fn() => hexa()->can('setor_sampah.update')),
        ];
    }
}