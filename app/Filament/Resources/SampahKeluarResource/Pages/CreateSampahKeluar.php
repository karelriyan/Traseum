<?php

namespace App\Filament\Resources\SampahKeluarResource\Pages;

use App\Filament\Resources\SampahKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSampahKeluar extends CreateRecord
{
    protected static string $resource = SampahKeluarResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('sampah_keluar.create');
    }
}
