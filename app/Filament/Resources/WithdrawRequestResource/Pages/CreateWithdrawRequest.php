<?php

namespace App\Filament\Resources\WithdrawRequestResource\Pages;

use App\Filament\Resources\WithdrawRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawRequest extends CreateRecord
{
    protected static string $resource = WithdrawRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Permintaan tarik saldo berhasil dibuat';
    }
}
