<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use App\Models\Rekening;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Support\Str;
use Filament\Support\Facades\Dialog;
class CreateRekening extends CreateRecord
{
    protected static string $resource = RekeningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Struktur Nomor Rekening: 1 (status desa) + 4 (tanggal pembuatan) + 3 (urut) = 8 digit

        // 1. Bagian Alamat (1 digit: 0 untuk dalam desa, 1 untuk luar desa)
        $addressPart = $data['status_desa'] ? '0' : '1';

        // 2. Bagian Tanggal (4 digit: ddmmyy)
        $datePart = Carbon::now()->format('my');

        // 3. Bagian Nomor Urut (3 digit)
        $lastRekeningToday = Rekening::whereDate('created_at', Carbon::today())->latest('id')->first();
        $sequence = 1;
        if ($lastRekeningToday) {
            $lastSequence = (int) substr($lastRekeningToday->no_rekening, -3);
            $sequence = $lastSequence + 1;
        }
        $sequencePart = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        $data['no_rekening'] = $addressPart . $datePart . $sequencePart;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Check the status_lengkap field of the record that was just created.
        // The value was set by the `saving` event in your Rekening model.
        if (!$this->record->status_lengkap) {
            Notification::make()
                ->title('Peringatan Data Belum Lengkap')
                ->body('Rekening berhasil dibuat, namun datanya belum lengkap. Mohon untuk melengkapinya segera.')
                ->warning() // Gives the notification a yellow/orange color
                ->persistent() // Requires the user to dismiss it
                ->send();
        }
    }
}