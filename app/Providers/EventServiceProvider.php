<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Models\SaldoTransaction;
use App\Models\SampahTransactions;
use App\Models\SetorSampah;
use App\Models\SampahKeluar;
use App\Observers\SaldoTransactionObserver;
use App\Observers\SampahTransactionsObserver;
use App\Observers\SetorSampahObserver;
use App\Observers\SampahKeluarObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     */
    public function boot(): void
    {
        SaldoTransaction::observe(SaldoTransactionObserver::class);
        SampahTransactions::observe(SampahTransactionsObserver::class);
        SetorSampah::observe(SetorSampahObserver::class);
        SampahKeluar::observe(SampahKeluarObserver::class);
    }

    /**

        * The event listener mappings for the application.
        *
        * @var array<class-string, array<int, class-string>>
        */
    protected $listen = [
        //
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array<int, class-string>
     */
    protected $subscribe = [
        //
    ];
}