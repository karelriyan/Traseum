<?php

namespace App\Filament\Resources\SampahKeluarResource\Pages;

use App\Filament\Resources\SampahKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSampahKeluar extends EditRecord
{
    protected static string $resource = SampahKeluarResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('sampah_keluar.update');
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->visible(fn() => hexa()->can('sampah_keluar.delete')),
        ];
    }
}
