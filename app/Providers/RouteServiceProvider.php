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

			$router->group(['before' => 'ui|csfr', 'middleware' => 'theme'], function($router) {

			
				// $router->get('session/start', array('as' => 'session.start', 'uses' => 'SessionController@getStart'));
				// $router->post('session/start', array('as' => 'session.post', 'uses' => 'SessionController@postStart'));
				$router->resource('session', 'SessionController', ['only' => ['store', 'create', 'index']]);
				

				$router->group(array('middleware' => 'auth'), function($router) {

					// $router->get('session/end', array('as' => 'session.end', 'uses' => 'SessionController@getEnd'));
					$router->get('/', array('as' => 'dash.index', 'uses' => 'DashController@getIndex'));

					$router->resource('tickets', 'TicketsController', ['except' => ['destroy']]); 
					
					$router->post('actions/{type?}', array('as' => 'actions.store', 'uses' => 'TicketActionsController@store'))->where('type', '(reply|comment|transfer|assign)');

					// $router->get('report/{report}', array('as' => 'report.index', 'uses' => 'ReportController@index'));

					$router->resource('reports', 'ReportsController', ['only' => ['index', 'show']]); 
					$router->resource('dev', 'DevController', ['only' => ['index']]); 
				});

			});

			$router->group(['namespace' => 'Api', 'prefix' => 'api', 'before' => 'auth|csfr'], function($router) {


				$router->resource('users', 'UsersController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
				$router->resource('tickets', 'TicketsController', ['except' => ['index', 'create', 'store', 'show', 'edit', 'destroy']]);

			});

		});
	}

}
