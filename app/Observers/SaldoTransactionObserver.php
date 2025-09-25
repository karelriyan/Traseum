<?php

namespace App\Observers;

use App\Models\SaldoTransaction;

class SaldoTransactionObserver
{
    /**
     * Handle the SaldoTransaction "created" event.
     */
    public function created(SaldoTransaction $saldoTransaction): void
    {
        $saldoTransaction->rekening?->recalculateBalance();
    }

    /**
     * Handle the SaldoTransaction "updated" event.
     */
    public function updated(SaldoTransaction $saldoTransaction): void
    {
        $saldoTransaction->rekening?->recalculateBalance();
    }

    /**
     * Handle the SaldoTransaction "deleted" event.
     */
    public function deleted(SaldoTransaction $saldoTransaction): void
    {
        $saldoTransaction->rekening?->recalculateBalance();
    }

    /**
     * Handle the SaldoTransaction "restored" event.
     */
    public function restored(SaldoTransaction $saldoTransaction): void
    {
        $saldoTransaction->rekening?->recalculateBalance();
    }
}