<?php

namespace App\Filament\Resources\WithdrawRequestResource\Pages;

use App\Filament\Resources\WithdrawRequestResource;
use App\Models\Rekening;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateWithdrawRequest extends CreateRecord
{
    protected static string $resource = WithdrawRequestResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('withdraw_request.create');
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Penarikan saldo berhasil dibuat';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validasi saldo mencukupi
        if (isset($data['rekening_id']) && isset($data['amount'])) {
            $rekening = Rekening::find($data['rekening_id']);
            if ($rekening && !$rekening->hasSufficientBalance($data['amount'])) {
                Notification::make()
                    ->title('Saldo Tidak Mencukupi')
                    ->body("Saldo tersedia: {$rekening->formatted_balance}. Jumlah penarikan: Rp " . number_format($data['amount'], 0, ',', '.'))
                    ->danger()
                    ->send();
                
                $this->halt();
            }
        }

        // Set default status
        $data['status'] = 'pending';
        $data['user_id'] = auth()->id();

        return $data;
    }
}
