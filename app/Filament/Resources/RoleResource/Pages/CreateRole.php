<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('role.create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['access'] = $data['gates'] ?? [];
        $data['guard'] = hexa()->guard();
        $data['created_by_name'] = Auth::user()->name;

        return $data;
    }
}

