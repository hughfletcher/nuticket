<?php namespace App\Services;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Auth\Access\Gate;
use Caffeinated\Menus\Builder;
use Illuminate\Auth\AuthManager;

class Menu {

	/**
	 * Create a Menu instance
	 *
	 * @param Illuminate\Foundation\Application $app
	 */
	public function __construct(Application $app, AuthManager $auth, Gate $gate)
    {
		$this->app = $app;
		$this->auth = $auth;
        $this->gate = $gate;
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

            if (!$this->auth->check()) {
                return false;
            }

            if (!isset($item->data['permissions'])) {
                return true;
            }

            if (is_array($item->data['permissions'])) {
                foreach ($item->data('permissions') as $permission) {
                    if ($this->gate->denies($permission)) {
                        return false;
                    }
                }
            }

  			return true;
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

				$menu->get(strtolower($namespace))->add($key, $route)->data('permissions', (isset($value['permissions']) ? $value['permissions'] : null));

			} else {

				$menu->add($key, $route)->data('permissions', (isset($value['permissions']) ? $value['permissions'] : null));

			}

			if (isset($value['children'])) {

				$this->build($value['children'], $menu, $key);
			}
		}

	}
}
