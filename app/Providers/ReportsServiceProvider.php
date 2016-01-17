<?php

namespace App\Providers;
use App\Services\Reports\Manager;

use Illuminate\Support\ServiceProvider;

class ReportsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\Reports\Manager', function ($app) {
            return new Manager($app);
        });
    }
}
