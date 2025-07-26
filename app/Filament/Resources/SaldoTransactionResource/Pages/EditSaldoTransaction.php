<?php

namespace App\Filament\Resources\SaldoTransactionResource\Pages;

use App\Filament\Resources\SaldoTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaldoTransaction extends EditRecord
{
    protected static string $resource = SaldoTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
