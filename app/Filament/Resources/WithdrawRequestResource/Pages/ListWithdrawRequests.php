<?php

namespace App\Filament\Resources\WithdrawRequestResource\Pages;

use App\Filament\Resources\WithdrawRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawRequests extends ListRecords
{
    protected static string $resource = WithdrawRequestResource::class;
    protected static ?string $title = 'Penarikan Saldo';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Penarikan Saldo')
                ->icon('heroicon-o-plus')
                ->visible(fn() => hexa()->can('withdraw_request.create')),
        ];
    }
}
