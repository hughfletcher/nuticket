<?php namespace Themes\Adminlte\Support;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Routing\Router;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(public_path() . '/themes/adminlte/support/lang', 'adminlte');
        
        $this->app['view']->composers(array(
            'App\Http\Composers\NavMenuComposer' => ['*'],
            'App\Http\Composers\ThemeComposer' => ['settings.system'],
            'App\Http\Composers\TicketsAssignedCountComposer' => ['tickets.index'],
            'App\Http\Composers\TicketsClosedCountComposer' => ['tickets.index'],
            'App\Http\Composers\TicketsOpenCountComposer' => ['tickets.index'],
            'App\Http\Composers\TicketPrioritiesComposer' => ['tickets.create', 'settings.system'],
            'App\Http\Composers\DeptComposer' => ['tickets.index', 'tickets.create', 'tickets.show', 'settings.system'],
            'App\Http\Composers\OrgComposer' => ['tickets.create', 'tickets.edit', 'tickets.index', 'settings.system'],
            'App\Http\Composers\StaffComposer' => ['tickets.index', 'tickets.create', 'tickets.show'],
            'App\Http\Composers\SettingsEmailsComposer' => ['settings.emails'],
        ));

        $router->group(array('middleware' => 'auth'), function($router) {
            $router->get('settings/theme/adminlte', ['as' => 'theme.adminlte.edit', 'uses' => 'Themes\AdminLte\Support\Controller@edit']);
            $router->put('settings/theme/adminlte', ['as' => 'theme.adminlte.update', 'uses' => 'Themes\AdminLte\Support\Controller@update']);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Radic\BladeExtensions\BladeExtensionsServiceProvider');
        $this->app->register('Collective\Html\HtmlServiceProvider');
        $this->app->register('Watson\BootstrapForm\BootstrapFormServiceProvider');

        $this->app->alias('Boot', 'Watson\BootstrapForm\Facades\BootstrapForm');
    }

}