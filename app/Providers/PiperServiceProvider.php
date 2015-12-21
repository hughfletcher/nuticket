<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Piper\Manager;

class PiperServiceProvider extends ServiceProvider
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
        // $this->app->singleton('piper', function () {
        //     return new PiperManager($this->app);
        // });
        $this->app->bind('App\Services\Piper\Manager', function ($app) {
            return new Manager($app);
        });
    }
}
