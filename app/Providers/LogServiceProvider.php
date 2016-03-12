<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ConfigInterface;
use Monolog\Handler\SwiftMailerHandler;
use Swift_Message;

class LogServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $monolog = $this->app['log']->getMonolog();

        foreach($monolog->getHandlers() as $handler) {
            $handler->setLevel($this->app['config']->get('settings.log.level'));
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}


}
