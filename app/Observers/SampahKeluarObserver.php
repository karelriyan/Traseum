<?php

namespace App\Observers;

use App\Models\SampahKeluar;
use App\Models\Rekening;
use Illuminate\Support\Facades\Auth;

class SampahKeluarObserver
{
    /**
     * Handle the SampahKeluar "creating" event.
     */
    public function creating(SampahKeluar $sampahKeluar): void
    {
        if (!$sampahKeluar->user_id && Auth::check()) {
            $sampahKeluar->user_id = Auth::id();
        }
    }

    /**
     * Handle the SampahKeluar "created" event.
     */
    public function created(SampahKeluar $sampahKeluar): void
    {
        // 1. Kurangi berat sampah terkait
        $sampahKeluar->sampah->details()->create([
            'type' => 'debit',
            'berat' => $sampahKeluar->berat,
            'transactable_id' => $sampahKeluar->id,
            'transactable_type' => SampahKeluar::class,
            'user_id' => $sampahKeluar->user_id,
        ]);

        // 2. Tambah saldo ke rekening bank sampah jika dijual (bukan dibakar)
        if (strtolower($sampahKeluar->jenis_keluar) !== 'dibakar' && $sampahKeluar->total_harga > 0) {
            $rekeningBankSampah = Rekening::where('no_rekening', '00000000')->first();
            if ($rekeningBankSampah) {
                $rekeningBankSampah->saldoTransactions()->create([
                    'amount' => $sampahKeluar->total_harga,
                    'type' => 'credit',
                    'description' => 'Penjualan Sampah: ' . $sampahKeluar->sampah->jenis_sampah,
                    'transactable_id' => $sampahKeluar->id,
                    'transactable_type' => SampahKeluar::class,
                    'user_id' => $sampahKeluar->user_id,
                ]);
            }
        }
    }

    /**
     * Handle the SampahKeluar "deleted" event.
     */
    public function deleted(SampahKeluar $sampahKeluar): void
    {
        // Hapus transaksi berat
        $sampahKeluar->sampah->details()->where('transactable_id', $sampahKeluar->id)->delete();

        // Hapus transaksi saldo jika ada
        $rekeningBankSampah = Rekening::where('no_rekening', '00000000')->first();
        if ($rekeningBankSampah) {
            $rekeningBankSampah->saldoTransactions()->where('transactable_id', $sampahKeluar->id)->delete();
        }
    }

    /**
     * Handle the SampahKeluar "restored" event.
     */
    public function restored(SampahKeluar $sampahKeluar): void
    {
        // Logika pemulihan: hapus sisa transaksi lama (jika ada) dan buat ulang
        $this->deleted($sampahKeluar);
        $this->created($sampahKeluar);
    }
}