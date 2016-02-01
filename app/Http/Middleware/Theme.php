<?php namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use App\Support\Theme as Themes;
use Closure;

class Theme
{

    public function __construct(Application $app, Themes $themes)
    {
        $this->app = $app;
        $this->themes = $themes;
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
        if ($this->app['request']->wantsJson()) {
            return $next($request);
        }

        // theme
        $theme = config('settings.theme');
        $path = public_path() . '/themes/' . $theme .'/';
        $config = $this->themes->config($theme);

        // provider
        if (isset($config['provider'])) {
            $this->app->register($config['provider']);
        }

        //views
        $views = 'views';
        if (isset($config['views'])) {
            $views = $config['views'];
        }
        $this->app['view']->addLocation($path . $views);      

        return $next($request);
    }

}
