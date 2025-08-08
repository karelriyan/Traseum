<?php

namespace App\Observers;

use App\Models\WithdrawRequest;
use App\Models\SaldoTransaction;
use App\Models\Rekening;
use Illuminate\Support\Facades\Auth;

class WithdrawRequestObserver
{
    /**
     * Handle the WithdrawRequest "updated" event.
     */
    public function updated(WithdrawRequest $withdrawRequest): void
    {
        // Check if status changed to approved
        if ($withdrawRequest->wasChanged('status') && $withdrawRequest->status === 'approved') {
            $this->createSaldoTransaction($withdrawRequest);
        }

        // Update processed information when status changes
        if ($withdrawRequest->wasChanged('status') && $withdrawRequest->status !== 'pending') {
            $withdrawRequest->processed_by = Auth::id();
            $withdrawRequest->processed_at = now();
            $withdrawRequest->saveQuietly();
        }
    }

    /**
     * Create a saldo transaction for the withdrawal
     */
    protected function createSaldoTransaction(WithdrawRequest $withdrawRequest): void
    {
        // Validasi saldo cukup sebelum membuat transaksi
        $rekening = $withdrawRequest->rekening;
        
        if (!$rekening->hasSufficientBalance($withdrawRequest->amount)) {
            throw new \Exception('Saldo tidak mencukupi untuk penarikan ini.');
        }

        // Create the saldo transaction with proper debit type
        SaldoTransaction::create([
            'rekening_id' => $withdrawRequest->rekening_id,
            'user_id' => Auth::id(),
            'type' => 'debit',
            'amount' => $withdrawRequest->amount,
            'transactable_type' => WithdrawRequest::class,
            'transactable_id' => $withdrawRequest->id,
            'description' => "Penarikan saldo - {$withdrawRequest->jenis} ({$withdrawRequest->amount})",
        ]);
    }

    /**
     * Handle the WithdrawRequest "deleting" event.
     */
    public function deleting(WithdrawRequest $withdrawRequest): void
    {
        // Delete associated saldo transaction if exists
        $withdrawRequest->saldoTransaction()->delete();
    }
}
