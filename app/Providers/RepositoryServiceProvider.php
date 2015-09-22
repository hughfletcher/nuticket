<?php namespace App\Providers; 

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('App\Repositories\ConfigInterface', 'App\Repositories\Eloquent\ConfigRepository');
        $this->app->bind('App\Repositories\DeptInterface', 'App\Repositories\Eloquent\DeptRepository');
        $this->app->bind('App\Repositories\FieldInterface', 'App\Repositories\Eloquent\FieldRepository');
        $this->app->bind('App\Repositories\ReportInterface', 'App\Repositories\Eloquent\ReportRepository');
        $this->app->bind('App\Contracts\Repositories\StaffInterface', 'App\Repositories\StaffRepository');
        $this->app->bind('App\Repositories\TicketActionInterface', 'App\Repositories\Eloquent\TicketActionRepository');
        $this->app->bind('App\Repositories\TicketInterface', 'App\Repositories\Eloquent\TicketRepository'); 
        $this->app->bind('App\Repositories\TimeLogInterface', 'App\Repositories\Eloquent\TimeLogRepository');
        $this->app->bind('App\Contracts\Repositories\UserInterface', 'App\Repositories\UserRepository');

    }

}