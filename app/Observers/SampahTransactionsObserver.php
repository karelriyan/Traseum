<?php

namespace App\Observers;

use App\Models\SampahTransactions;

class SampahTransactionsObserver
{
    /**
     * Handle the SampahTransactions "created" event.
     */
    public function created(SampahTransactions $sampahTransactions): void
    {
        $sampahTransactions->sampah?->recalculateTotalBerat();
    }

    /**
     * Handle the SampahTransactions "updated" event.
     */
    public function updated(SampahTransactions $sampahTransactions): void
    {
        $sampahTransactions->sampah?->recalculateTotalBerat();
    }

    /**
     * Handle the SampahTransactions "deleted" event.
     */
    public function deleted(SampahTransactions $sampahTransactions): void
    {
        $sampahTransactions->sampah?->recalculateTotalBerat();
    }

    /**
     * Handle the SampahTransactions "restored" event.
     */
    public function restored(SampahTransactions $sampahTransactions): void
    {
        $sampahTransactions->sampah?->recalculateTotalBerat();
    }
}