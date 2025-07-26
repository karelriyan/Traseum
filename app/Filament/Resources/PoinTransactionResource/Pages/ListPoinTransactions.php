<?php

namespace App\Filament\Resources\PoinTransactionResource\Pages;

use App\Filament\Resources\PoinTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPoinTransactions extends ListRecords
{
    protected static string $resource = PoinTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
