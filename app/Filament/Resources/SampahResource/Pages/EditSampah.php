<?php

namespace App\Filament\Resources\SampahResource\Pages;

use App\Filament\Resources\SampahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSampah extends EditRecord
{
    protected static string $resource = SampahResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('sampah.update');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->visible(fn() => hexa()->can('sampah.delete')),
        ];
    }
}
