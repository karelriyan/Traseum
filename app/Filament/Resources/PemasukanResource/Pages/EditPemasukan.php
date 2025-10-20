<?php

namespace App\Filament\Resources\PemasukanResource\Pages;

use App\Filament\Resources\PemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemasukan extends EditRecord
{
    protected static string $resource = PemasukanResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('pemasukan.update');
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->visible(fn() => hexa()->can('pemasukan.delete')),
        ];
    }
}
