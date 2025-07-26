<?php

namespace App\Filament\Resources\PostinganUmkmResource\Pages;

use App\Filament\Resources\PostinganUmkmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostinganUmkm extends EditRecord
{
    protected static string $resource = PostinganUmkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
