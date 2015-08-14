<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Workbench\Starter;

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
		$this->develop();
		
	}

	public function develop() 
	{
		if ($this->app->environment('local')) 
		{
			$this->app->register('Clockwork\Support\Laravel\ClockworkServiceProvider');
		}

	}

}
