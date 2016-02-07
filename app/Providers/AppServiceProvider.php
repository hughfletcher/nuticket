<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Workbench\Starter;
use App\Services\Notify\SendMessage;
use App\Services\Piper\Manager as PiperManager;
use App\Services\Reports\Manager as ReportsManager;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{

	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// $this->registerNotify();
		$this->registerPiper();
		$this->registerReports();
	}

	public function registerPiper()
	{
		$this->app->bind('App\Services\Piper\Manager', function ($app) {
            return new PiperManager($app);
        });
	}

	public function registerNotify()
	{
		$this->app['notifynder']->extend('sendMessage', function($notifications,$app) {
            return new SendMessage($notifications);
        });
	}

	public function registerReports()
    {
        $this->app->singleton('App\Services\Reports\Manager', function ($app) {
            return new ReportsManager($app);
        });
    }

}
