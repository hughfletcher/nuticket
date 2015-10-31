<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ConfigInterface;

class ConfigServiceProvider extends ServiceProvider
{

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
        if (!$this->app['db']->connection()->getSchemaBuilder()->hasTable('config'))
        {
            return;
        }

        $config = $this->app->make('App\Repositories\ConfigInterface');

        $values = $config->all();

        foreach ($values as $row) {

            if ($this->app['config']->get($row->key) == $row->value)
            {
                $config->delete($row->id);
            }
            else
            {
                $this->app['config']->set($row->key, $row->value);
            }

       }

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}


}
