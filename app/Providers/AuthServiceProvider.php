<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $this->definePolicies($gate);

        $gate->before(function ($user) {
            if ($user->is_admin) {
                return true;
            }
        });
    }

    public function definePolicies(GateContract $gate)
    {
        $gate->define('use-tags', function ($user) {
            return $user->is_staff;
        });

        $gate->define('isStaff', function ($user) {
            return $user->is_staff;
        });
    }
}
