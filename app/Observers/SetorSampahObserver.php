<?php

namespace App\Observers;

use App\Models\SetorSampah;
use App\Models\SaldoTransaction;
use App\Models\PoinTransaction;
use Illuminate\Support\Facades\DB;

class SetorSampahObserver
{
    /**
     * Handle the SetorSampah "created" event.
     */
    public function created(SetorSampah $setorSampah)
    {
        $rekening = $setorSampah->rekening;
        if (!$rekening) {
            return;
        }

        DB::transaction(function () use ($rekening, $setorSampah) {
            // 1. Update summary columns on the rekening table
            $rekening->increment('balance', $setorSampah->total_saldo_dihasilkan);
            $rekening->increment('points_balance', $setorSampah->total_poin_dihasilkan);

            // 2. Create a credit transaction for saldo
            if ($setorSampah->total_saldo_dihasilkan > 0) {
                SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_saldo_dihasilkan,
                    'type' => 'credit',
                    'description' => 'Penambahan saldo dari setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }

            // 3. Create a transaction for poin
            if ($setorSampah->total_poin_dihasilkan > 0) {
                PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_poin_dihasilkan,
                    'description' => 'Penambahan poin dari setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }
        });
    }
    /**
     * Handle the SetorSampah "deleted" event.
     */
    public function deleted(SetorSampah $setorSampah)
    {
        $rekening = $setorSampah->rekening;
        if (!$rekening) {
            return;
        }

        DB::transaction(function () use ($rekening, $setorSampah) {
            // 1. Update summary columns on the rekening table
            $rekening->decrement('balance', $setorSampah->total_saldo_dihasilkan);
            $rekening->decrement('points_balance', $setorSampah->total_poin_dihasilkan);

            // 2. Create a reversing debit transaction for saldo
            if ($setorSampah->total_saldo_dihasilkan > 0) {
                SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_saldo_dihasilkan, // Amount is always positive for debit
                    'type' => 'debit',
                    'description' => 'Pembatalan saldo dari hapus setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah_reversal',
                ]);
            }

            // 3. Create a reversing transaction for poin
            if ($setorSampah->total_poin_dihasilkan > 0) {
                PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => -$setorSampah->total_poin_dihasilkan,
                    'description' => 'Pembatalan poin dari hapus setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah_reversal',
                ]);
            }
        });
    }

    /**
     * Handle the SetorSampah "restored" event.
     */
    public function restored(SetorSampah $setorSampah)
    {
        $rekening = $setorSampah->rekening;
        if (!$rekening) {
            return;
        }

        DB::transaction(function () use ($rekening, $setorSampah) {
            // 1. Update summary columns on the rekening table
            $rekening->increment('balance', $setorSampah->total_saldo_dihasilkan);
            $rekening->increment('points_balance', $setorSampah->total_poin_dihasilkan);

            // 2. Create a new credit transaction to re-apply the balance
            if ($setorSampah->total_saldo_dihasilkan > 0) {
                SaldoTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_saldo_dihasilkan,
                    'type' => 'credit',
                    'description' => 'Pengembalian saldo dari pemulihan setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }

            // 3. Create a new transaction to re-apply the points
            if ($setorSampah->total_poin_dihasilkan > 0) {
                PoinTransaction::create([
                    'rekening_id' => $rekening->id,
                    'amount' => $setorSampah->total_poin_dihasilkan,
                    'description' => 'Pengembalian poin dari pemulihan setor sampah',
                    'transactable_id' => $setorSampah->id,
                    'transactable_type' => 'setor_sampah',
                ]);
            }
        });
    }

    /**
     * Handle the SetorSampah "forceDeleting" event.
     */
    public function forceDeleting(SetorSampah $setorSampah)
    {
        // The balance is already reduced from the 'deleted' event.
        // Here, we just clean up the child records that don't have cascading deletes.
        $setorSampah->details()->forceDelete();
    }
}
