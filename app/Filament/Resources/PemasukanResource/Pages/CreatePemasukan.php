<?php

namespace App\Filament\Resources\PemasukanResource\Pages;

use App\Filament\Resources\PemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePemasukan extends CreateRecord
{
    protected static string $resource = PemasukanResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('pemasukan.create');
    }
}
