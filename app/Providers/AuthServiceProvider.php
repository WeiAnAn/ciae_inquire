<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];  

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('permission',function($user, $data){
            if(($user->permission < 2 )|| 
                ($user->permission == 2 && $user->college == $data->college) ||
                ($user->permission == 3 && $user->college == $data->college && 
                $data->dept == $data->dept))
                return true;
            return false;
        });
        Gate::define('superUser',function($user){
            return ($user->permission == 0);
        });
        //
    }
}
