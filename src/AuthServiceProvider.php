<?php

namespace ZoutApps\LaravelBackpackAuth;

use Illuminate\Support\ServiceProvider;
use ZoutApps\LaravelBackpackAuth\Commands\BackpackMultiAuth;
use ZoutApps\LaravelBackpackAuth\Commands\MultiAuth;
use ZoutApps\LaravelBackpackAuth\Commands\RoleAuth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->mergeConfigFrom(
            __DIR__.'/config/zoutapps/multiauth.php', 'zoutapps.multiauth'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSetupCommand();
    }

    private function registerSetupCommand()
    {
        $this->commands([
            MultiAuth::class,
            RoleAuth::class,
            BackpackMultiAuth::class
        ]);
    }
}
