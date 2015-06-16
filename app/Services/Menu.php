<?php namespace App\Services;

use Illuminate\Foundation\Application;
use Caffeinated\Menus\Builder;
use Auth;

class Menu {

	/**
	 * Create a Menu instance
	 * 			
	 * @param Illuminate\Foundation\Application $app
	 */
	public function __construct(Application $app) {
		$this->app = $app;
	}

	/**
	 * Create a Menu
	 * 	
	 * @param  string $namespace
	 * @param  array|null $config
	 * @return void
	 */
	public function make($namespace, array $config = null) {

		if (!$config) {
			$config = $this->app['config']->get('menu');
		}
		

		$this->app['menu']->make($namespace, function($menu) use($config) {
		  	
		  	$this->build($config, $menu);


		})->filter(function($item){
			if ($item->data('public')) { return true; }

			if (!Auth::check()) { return false; }
			
			$staff = $this->app['auth']->user()->staff;
			if (!$item->data('public') && !empty($staff)) {
				return true;
			}
  			return false;
		});;
	}

	/**
	 * Build a level of menu
	 * 
	 * @param  array  $config 
	 * @param  Caffeinated\Menus\Builder $menu
	 * @param  string|null $namespace
	 * @return void
	 */
	protected function build(array $config, Builder $menu, $namespace = null) {

		foreach ($config as $key => $value) {

			$route = null;

			if (isset($value['route'])) {
				$route = ['route' => $value['route']];
			}

			if (isset($value['url'])) {
				$route = $value['url'];
			}

			if ($namespace) {

				$menu->get(strtolower($namespace))->add($key, $route)->data('public', $value['public']);

			} else {

				$menu->add($key, $route)->data('public', $value['public']);

			}
			
			if (isset($value['children'])) {

				$this->build($value['children'], $menu, $key);
			}
		}
		
	}
}