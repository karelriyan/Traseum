<?php

namespace App\Observers;

use App\Models\SampahKeluar;
use App\Models\Rekening;
use APP\Models\User;
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
        if ($sampahKeluar->total_saldo_dihasilkan > 0) {
            $sampahKeluar->rekening->saldoTransactions()->create([
                'amount' => $sampahKeluar->total_saldo_dihasilkan,
                'type' => 'credit',
                'description' => 'Penjualan Sampah Keluar',
                'transactable_id' => $sampahKeluar->id,
                'transactable_type' => sampahKeluar::class,
                'user_id' => $sampahKeluar->user_id,
            ]);
        }
    }

    /**
     * Handle the SampahKeluar "deleted" event.
     */
    public function deleted(SampahKeluar $sampahKeluar): void
    {
        // Hapus (soft delete) semua transaksi terkait satu per satu untuk memicu observer mereka.
        $sampahKeluar->details()->get()->each->delete();
        $sampahKeluar->rekening->saldoTransactions()->where('transactable_id', $sampahKeluar->id)->get()->each->delete();
    }

    /**
     * Handle the SampahKeluar "restored" event.
     */
    public function restored(SampahKeluar $sampahKeluar): void
    {
        // Pulihkan (restore) semua transaksi terkait satu per satu untuk memicu observer mereka.
        $sampahKeluar->details()->onlyTrashed()->where('transactable_id', $sampahKeluar->id)->get()->each->restore();
        $sampahKeluar->rekening->saldoTransactions()->onlyTrashed()->where('transactable_id', $sampahKeluar->id)->get()->each->restore();

    }

    public function forceDeleted(SampahKeluar $sampahKeluar): void
    {
        // Hapus permanen semua transaksi terkait
        $sampahKeluar->details()->withTrashed()->where('transactable_id', $sampahKeluar->id)->get()->each->forceDelete();
        $sampahKeluar->rekening->saldoTransactions()->withTrashed()->where('transactable_id', $sampahKeluar->id)->get()->each->forceDelete();
    }
}