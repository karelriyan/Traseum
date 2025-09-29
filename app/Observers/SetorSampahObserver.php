<?php

namespace App\Observers;

use App\Models\SetorSampah;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SetorSampahObserver
{
    /**
     * Handle the SetorSampah "creating" event.
     */
    public function creating(SetorSampah $setorSampah): void
    {
        if (!$setorSampah->user_id && Auth::check()) {
            $setorSampah->user_id = Auth::id();
        }

        if (($setorSampah->jenis_setoran ?? null) === 'donasi') {
            $rekening = Rekening::firstOrCreate(
                ['no_rekening' => '00000000'],
                [
                    'nama' => 'Kas Bank Sampah (Donasi)',
                    'user_id' => User::first()->id ?? throw new \Exception('Sistem membutuhkan minimal satu user.'),
                    'nik' => '0000000000000000',
                    'no_kk' => '0000000000000000',
                    'gender' => 'Laki-laki',
                    'tanggal_lahir' => now()->subYears(20)->toDateString(),
                    'pendidikan' => 'TIDAK/BELUM SEKOLAH',
                    'dusun' => '0',
                    'rw' => '0',
                    'rt' => '0',
                ]
            );
            $setorSampah->rekening_id = $rekening->id;
            $setorSampah->total_saldo_dihasilkan = 0;
            $setorSampah->total_poin_dihasilkan = 0;
        }
    }

    /**
     * Handle the SetorSampah "created" event.
     */
    public function created(SetorSampah $setorSampah): void
    {

        // Buat transaksi saldo hanya jika bukan donasi dan ada saldo
        if (!$setorSampah->isDonation() && $setorSampah->total_saldo_dihasilkan > 0) {
            $setorSampah->rekening->saldoTransactions()->create([
                'amount' => $setorSampah->total_saldo_dihasilkan,
                'type' => 'credit',
                'description' => 'Setoran Sampah',
                'transactable_id' => $setorSampah->id,
                'transactable_type' => SetorSampah::class,
                'user_id' => $setorSampah->user_id,
            ]);
        }
    }

    /**
     * Handle the SetorSampah "deleted" event.
     */
    public function deleted(SetorSampah $setorSampah): void
    {
        // Hapus semua transaksi terkait
        $setorSampah->details()->where('transactable_id', $setorSampah->id)->delete();
        $setorSampah->rekening->saldoTransactions()->where('transactable_id', $setorSampah->id)->delete();
    }

    /**
     * Handle the SetorSampah "restored" event.
     */
    public function restored(SetorSampah $setorSampah): void
    {
        $setorSampah->details()->withTrashed()->where('transactable_id', $setorSampah->id)->get()->each->restore();
        $setorSampah->rekening->saldoTransactions()->withTrashed()->where('transactable_id', $setorSampah->id)->get()->each->restore();
    }

    public function forceDeleted(SetorSampah $setorSampah): void
    {
        // Hapus permanen semua transaksi terkait
        $setorSampah->details()->withTrashed()->where('transactable_id', $setorSampah->id)->get()->each->forceDelete();
        $setorSampah->rekening->saldoTransactions()->withTrashed()->where('transactable_id', $setorSampah->id)->get()->each->forceDelete();
    }
}