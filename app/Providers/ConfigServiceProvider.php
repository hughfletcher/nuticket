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

            // This is done to migrate from straight values in config db to serialized values
            // Error suppressing on unserialize() and array key checking should be removed later.
            // Later if error appears it will be an issue elsewhere inputting the value and not here.
            $value = @unserialize($row->value);

            if (!$value || !isset($value[0])) {
                continue;
            }

            if ($this->app['config']->get($row->key) === $value[0])
            {
                $config->delete($row->id);
            }
            else
            {
                $this->app['config']->set($row->key, $value[0]);
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
