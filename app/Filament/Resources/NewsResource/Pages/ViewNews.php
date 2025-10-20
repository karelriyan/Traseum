<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNews extends ViewRecord
{
    protected static string $resource = NewsResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('berita.index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->visible(fn() => hexa()->can('berita.update')),
            Actions\DeleteAction::make()
            ->visible(fn()=>hexa()->can('berita.delete')),
        ];
    }
}
