<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Ldap\LdapAuthUserProvider;
use App\Services\Ldap\LdapLocal;
use adLDAP\adLDAP;

class LdapServiceProvider extends ServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->app['auth']->extend('ldap', function($app)
        {
            return new LdapAuthUserProvider($app['ldap'], $app['config']['auth'], $app['config']['auth.model']);
        });
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ldap', function()
        {
            return new adLDAP($this->getLdapConfig());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('ldap');
    }

    protected function getLdapConfig()
    {
        if (is_array($this->app['config']['adldap'])) return $this->app['config']['adldap'];

        return array();
    }
}