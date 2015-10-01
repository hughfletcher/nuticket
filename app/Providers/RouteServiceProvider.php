<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		//
		
		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{

			$router->group(['middleware' => 'theme'], function($router) {
				$router->get('/', array('as' => 'dash.index', 'uses' => 'DashController@getIndex'));

				$router->resource('session', 'SessionController', ['only' => ['store', 'create', 'index']]);
				

				$router->group(array('middleware' => 'auth'), function($router) {

					$router->resource('tickets', 'TicketsController', ['except' => ['destroy']]); 
					$router->resource('actions', 'TicketActionsController', ['only' => ['store']]);
					$router->resource('reports', 'ReportsController', ['only' => ['index', 'show']]);

					$router->group(['prefix' => 'me'], function($router) {
						$router->resource('time', 'TimeController');

					});
					
					// 	'index' => 'me.time.index',
					// 	'store' => 'me.time.store',
					// 	'edit' => 'me.time.edit',
					// 	'update' => 'me.time.update',
					// 	'delete' => 'me.time.delete',
					// 	'destroy' => 'me.time.destroy',
					// ]]);
				});

			});

			$router->group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => 'auth'], function($router) {


				$router->resource('users', 'UsersController', ['except' => ['create', 'edit', 'update', 'destroy']]);
				$router->resource('staff', 'StaffController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
				$router->resource('tickets', 'TicketsController', ['except' => ['index', 'create', 'store', 'show', 'edit', 'destroy']]);

			});

		});
	}

}
