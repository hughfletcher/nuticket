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
		$this->registeraLogLevel();
	}

	public function registerPiper()
	{
		$this->app->bind('App\Services\Piper\Manager', function ($app) {
            return new PiperManager($app);
        });
	}

	public function registerReports()
    {
        $this->app->singleton('App\Services\Reports\Manager', function ($app) {
            return new ReportsManager($app);
        });
    }

    public function registeraLogLevel()
    {
    	$monolog = $this->app['log']->getMonolog();
	    foreach($monolog->getHandlers() as $handler) {
	      	$handler->setLevel($this->app['config']->get('settings.log.level'));
	    }
    }

}
