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
		if ($this->app->environment('production')) 
		{
			return;
		}

		$this->app->register('Clockwork\Support\Laravel\ClockworkServiceProvider');
		// $this->app->register('Illuminate\Workbench\WorkbenchServiceProvider');

		// if (is_dir($workbench = base_path() . '/workbench'))
		// {
		//     Starter::start($workbench);
		// }

		// if (is_file(base_path() . '/.workbench.php'))
		// {
		// 	include(base_path() . '/.workbench.php');
		// }

	}

}
