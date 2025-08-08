<?php

namespace App\Observers;

use App\Models\SetorSampah;

class SetorSampahObserver
{
    /**
     * Handle the SetorSampah "updated" event.
     */
    public function updated(SetorSampah $setorSampah)
    {
        if ($setorSampah->isDirty(['sampah_id', 'berat'])) {
            $rekening = $setorSampah->rekening;
            
            if ($rekening) {
                // Hitung selisih perubahan
                $oldSaldo = $setorSampah->getOriginal('total_saldo_dihasilkan');
                $oldPoin = $setorSampah->getOriginal('total_poin_dihasilkan');
                $newSaldo = $setorSampah->total_saldo_dihasilkan;
                $newPoin = $setorSampah->total_poin_dihasilkan;
                
                $deltaSaldo = $newSaldo - $oldSaldo;
                $deltaPoin = $newPoin - $oldPoin;
                
                // Update saldo dan poin rekening
                $rekening->balance += $deltaSaldo;
                $rekening->points_balance += $deltaPoin;
                $rekening->save();
                
                // Buat transaksi saldo untuk perubahan
                if ($deltaSaldo != 0) {
                    \App\Models\SaldoTransaction::create([
                        'rekening_id' => $rekening->id,
                        'amount' => $deltaSaldo,
                        'type' => $deltaSaldo > 0 ? 'credit' : 'debit',
                        'description' => 'Perubahan saldo dari update setor sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => 'setor_sampah',
                    ]);
                }

                // Buat transaksi poin untuk perubahan
                if ($deltaPoin != 0) {
                    \App\Models\PoinTransaction::create([
                        'rekening_id' => $rekening->id,
                        'amount' => $deltaPoin,
                        'description' => 'Perubahan poin dari update setor sampah',
                        'transactable_id' => $setorSampah->id,
                        'transactable_type' => 'setor_sampah',
                    ]);
                }
            }
        }
    }

    /**
     * Handle the SetorSampah "deleted" event.
     */
    public function deleted(SetorSampah $setorSampah)
    {
        $rekening = $setorSampah->rekening;
        
        if ($rekening) {
            // Kurangi saldo dan poin dari rekening
            $rekening->balance -= $setorSampah->total_saldo_dihasilkan;
            $rekening->points_balance -= $setorSampah->total_poin_dihasilkan;
            $rekening->save();
            
            // Buat transaksi saldo untuk pengurangan
            \App\Models\SaldoTransaction::create([
                'rekening_id' => $rekening->id,
                'amount' => -$setorSampah->total_saldo_dihasilkan,
                'type' => 'debit',
                'description' => 'Pengurangan saldo dari penghapusan setor sampah',
                'transactable_id' => $setorSampah->id,
                'transactable_type' => 'setor_sampah',
            ]);
            
            // Buat transaksi poin untuk pengurangan
            \App\Models\PoinTransaction::create([
                'rekening_id' => $rekening->id,
                'amount' => -$setorSampah->total_poin_dihasilkan,
                'description' => 'Pengurangan poin dari penghapusan setor sampah',
                'transactable_id' => $setorSampah->id,
                'transactable_type' => 'setor_sampah',
            ]);
        }
    }

    /**
     * Handle the SetorSampah "restored" event.
     */
    public function restored(SetorSampah $setorSampah)
    {
        $rekening = $setorSampah->rekening;
        
        if ($rekening) {
            // Tambahkan kembali saldo dan poin ke rekening
            $rekening->balance += $setorSampah->total_saldo_dihasilkan;
            $rekening->points_balance += $setorSampah->total_poin_dihasilkan;
            $rekening->save();
            
            // Buat transaksi saldo untuk penambahan kembali
            \App\Models\SaldoTransaction::create([
                'rekening_id' => $rekening->id,
                'amount' => $setorSampah->total_saldo_dihasilkan,
                'type' => 'credit',
                'description' => 'Penambahan saldo dari restore setor sampah',
                'transactable_id' => $setorSampah->id,
                'transactable_type' => 'setor_sampah',
            ]);
            
            // Buat transaksi poin untuk penambahan kembali
            \App\Models\PoinTransaction::create([
                'rekening_id' => $rekening->id,
                'amount' => $setorSampah->total_poin_dihasilkan,
                'type' => 'credit',
                'description' => 'Penambahan poin dari restore setor sampah',
                'transactable_id' => $setorSampah->id,
                'transactable_type' => 'setor_sampah',
            ]);
        }
    }
}
