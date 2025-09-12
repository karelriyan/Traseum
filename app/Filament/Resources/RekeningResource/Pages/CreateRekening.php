<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use App\Models\Rekening;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateRekening extends CreateRecord
{
    protected static string $resource = RekeningResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Struktur Nomor Rekening: 5 (alamat) + 3 (urut) = 8 digit

        // 1. Bagian Alamat (5 digit: 1 dusun, 2 RW, 2 RT)
        $dusun = $data['dusun'];
        $rw = str_pad($data['rw'], 2, '0', STR_PAD_LEFT);
        $rt = str_pad($data['rt'], 2, '0', STR_PAD_LEFT);
        $addressPart = $dusun . $rw . $rt;

        // 3. Bagian Nomor Urut (3 digit)
        $lastRekeningToday = Rekening::whereDate('created_at', Carbon::today())->latest('id')->first();
        $sequence = 1;
        if ($lastRekeningToday) {
            $lastSequence = (int) substr($lastRekeningToday->no_rekening, -3);
            $sequence = $lastSequence + 1;
        }
        $sequencePart = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        $data['no_rekening'] = $addressPart . $sequencePart;

        return $data;
    }
}