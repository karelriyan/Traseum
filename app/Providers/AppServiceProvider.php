<?php

namespace App\Providers;

use App\Models\SetorSampah;
use App\Models\WithdrawRequest;
use App\Observers\SetorSampahObserver;
use App\Observers\WithdrawRequestObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        SetorSampah::observe(SetorSampahObserver::class);
        WithdrawRequest::observe(WithdrawRequestObserver::class);
    }
}
