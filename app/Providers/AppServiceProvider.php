<?php

namespace App\Providers;

use App\Jobs\FetchData;
use Illuminate\Support\Facades\Cache;
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
        if(!Cache::has('directories')) {
            FetchData::dispatch();
        }
    }
}
