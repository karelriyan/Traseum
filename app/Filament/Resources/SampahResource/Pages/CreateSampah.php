<?php

namespace App\Filament\Resources\SampahResource\Pages;

use App\Filament\Resources\SampahResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSampah extends CreateRecord
{
    protected static string $resource = SampahResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('sampah.create');
    }
}
