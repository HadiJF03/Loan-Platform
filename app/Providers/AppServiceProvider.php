<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pledge;
use App\Models\Offer;
use App\Models\Transaction;
use App\Policies\OfferPolicy;
use App\Policies\PledgePolicy;
use App\Policies\TransactionPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pledge::class => PledgePolicy::class,
        Offer::class => OfferPolicy::class,
        Transaction::class => TransactionPolicy::class,
    ];
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
        //
    }
}
