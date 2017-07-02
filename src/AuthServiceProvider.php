<?php

namespace ZoutApps\LaravelBackpackAuth;

use Illuminate\Support\ServiceProvider;
use ZoutApps\LaravelBackpackAuth\Console\Commands\BackpackAuthCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Helper\BackpackFilesCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Helper\FilesCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Helper\ModelCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Helper\RoleModelCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Helper\SettingsCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Helper\ViewsCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\MultiAuthCommand;
use ZoutApps\LaravelBackpackAuth\Console\Commands\RoleAuthCommand;


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
            __DIR__ . '/config/zoutapps/multiauth.php', 'zoutapps.multiauth'
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
            MultiAuthCommand::class,
            BackpackAuthCommand::class,
            RoleAuthCommand::class,

            //Helper
            SettingsCommand::class,
            FilesCommand::class,
            ModelCommand::class,
            ViewsCommand::class,
            BackpackFilesCommand::class,
            RoleModelCommand::class
        ]);
    }
}
