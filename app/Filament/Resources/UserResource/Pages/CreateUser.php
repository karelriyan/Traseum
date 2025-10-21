<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Hexters\HexaLite\Facades\Hexa;



class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('user.create');
    }

    protected function afterCreate(): void
    {
        // Warm caches for the created user to avoid stale permission issues immediately after creation
        $this->record->refreshCache();
    }
}
