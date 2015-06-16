<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Form\Form;

class FormServiceProvider extends ServiceProvider {

	protected $defer = true;

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
		$this->app->bindShared('nut.form', function($app) {
			return new Form();
		});
	}

}
