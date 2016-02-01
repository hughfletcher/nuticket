<?php namespace App\Http\Composers;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Auth\Access\Gate;
use Caffeinated\Menus\Menu as CaffeinatedMenu;
use Caffeinated\Menus\Builder;
use Illuminate\Auth\AuthManager;

class NavMenuComposer {

    public function __construct(CaffeinatedMenu $menu, Config $config, AuthManager $auth, Gate $gate)
    {
		$this->config = $config;
		$this->menu = $menu;
		$this->auth = $auth;
        $this->gate = $gate;
	}

    public function compose($view)
    {
        $this->make('nav');

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
			$config = $this->config->get('menu');
		}

        $this->menu->make($namespace, function($menu) use ($config) {
            $this->build($config, $menu);


		})->filter(function($item){
            return $this->filter($item);

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
	public function build(array $config, Builder $menu, $namespace = null) {

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

    public function filter($item)
    {
        if (!$this->auth->check()) {
            return false;
        }

        if (!isset($item->data['permissions'])) {
            return true;
        }

        if (is_array($item->data['permissions'])) {
            foreach ($item->data('permissions') as $permission) {
                if ($this->gate->allows($permission)) {
                    return true;
                }
            }
        }

        return false;
    }

}