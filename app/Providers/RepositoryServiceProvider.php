<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('App\Repositories\ConfigInterface', 'App\Repositories\Eloquent\ConfigRepository');
        $this->app->bind('App\Repositories\DeptInterface', 'App\Repositories\Eloquent\DeptRepository');
        $this->app->bind('App\Contracts\Repositories\EmailInterface', 'App\Repositories\EmailRepository');
        $this->app->bind('App\Repositories\FieldInterface', 'App\Repositories\Eloquent\FieldRepository');
        $this->app->bind('App\Contracts\Repositories\OrgInterface', 'App\Repositories\OrgRepository');
        $this->app->bind('App\Repositories\ReportInterface', 'App\Repositories\Eloquent\ReportRepository');
        $this->app->bind('App\Repositories\TicketActionInterface', 'App\Repositories\Eloquent\TicketActionRepository');
        $this->app->bind('App\Contracts\Repositories\TicketActionInterface', 'App\Repositories\TicketActionRepository');
        $this->app->bind('App\Contracts\Repositories\TicketInterface', 'App\Repositories\TicketRepository');
        $this->app->bind('App\Repositories\TimeLogInterface', 'App\Repositories\Eloquent\TimeLogRepository');
        $this->app->bind('App\Contracts\Repositories\TimeLogInterface', 'App\Repositories\TimeLogRepository');
        $this->app->bind('App\Contracts\Repositories\UserInterface', 'App\Repositories\UserRepository');

    }

}
