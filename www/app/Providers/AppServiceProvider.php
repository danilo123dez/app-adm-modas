<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Releases;
use App\Models\Stores;
use App\Models\User;
use App\Observers\CustomerObserver;
use App\Observers\ReleasesObserver;
use App\Observers\StoresObserver;
use App\Observers\UserOberserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Customer::observe(CustomerObserver::class);
        User::observe(UserOberserver::class);
        Stores::observe(StoresObserver::class);
        Releases::observe(ReleasesObserver::class);
    }
}
