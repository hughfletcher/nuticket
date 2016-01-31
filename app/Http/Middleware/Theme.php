<?php namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use App\Support\Themes;
use App\Services\Menu;
use Closure;

class Theme
{

    public function __construct(Application $app, Themes $themes, Menu $menu)
    {
        $this->app = $app;
        $this->themes = $themes;
        $this->menu= $menu;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // theme
        $theme = config('settings.theme');
        $path = public_path() . '/themes/' . $theme .'/';
        $config = $this->themes->config($theme);

        //views
        $views = 'views';
        if (isset($config['views'])) {
            $views = $config['views'];
        }
        $this->app['view']->addLocation($path . $views);

        //includes
        if (isset($config['includes'])) {
            foreach ($config['includes'] as $file) {
                include($path . $file);
            }
        }
        

        // menu
        $this->menu->make('nav');

        // composers
        if (isset($config['composers'])) {
            $this->app['view']->composers($config['composers']);
        }

        return $next($request);
    }

}
