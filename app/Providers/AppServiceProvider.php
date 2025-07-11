<?php

namespace App\Providers;

use Carbon\Carbon;
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
        // Set locale ke Indonesia
        setlocale(LC_ALL, 'id_ID.UTF8');

        // Set locale Carbon ke Indonesia
        Carbon::setLocale('id_ID');
    }
}
