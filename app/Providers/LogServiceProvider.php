<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ConfigInterface;
use Monolog\Handler\SwiftMailerHandler;
use Swift_Message, Logger;

class LogServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $monolog = $this->app['log']->getMonolog();

        $user = $this->app->make('App\Contracts\Repositories\UserInterface');
        $admins = $user->findAllBy('is_admin', true)->lists('display_name', 'email');

        foreach($monolog->getHandlers() as $handler) {
            $handler->setLevel($this->app['config']->get('settings.log.level'));
        }

        if ($this->app->getEnviroment() == 'production' && config('notify.system.admin')) {
            $monolog->pushHandler(
                new SwiftMailerHandler(
                    $this->app['mailer']->getSwiftMailer(),
                    Swift_Message::newInstance('[Log] An Error Occured!')->setFrom(config('settings.mail.admin'))->setTo($admins->toArray()),
                    'error', // set minimal log lvl for mail
                    true // bubble to next handler?
                )
            );
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {}


}
