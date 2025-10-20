<?php

namespace App\Filament\Resources\SetorSampahResource\Pages;

use App\Filament\Resources\SetorSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetorSampah extends EditRecord
{
    protected static string $resource = SetorSampahResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('setor_sampah.update');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->visible(fn() => hexa()->can('setor_sampah.delete')),
        ];
    }
}
