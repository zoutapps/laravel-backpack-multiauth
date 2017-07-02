<?php

namespace ZoutApps\LaravelBackpackMultiAuth;

use Illuminate\Support\ServiceProvider;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\BackpackAuthCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\BackpackFilesCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\FilesCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\ModelCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\RoleModelCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\SettingsCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\ViewsCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\MultiAuthCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\RoleAuthCommand;


class MultiAuthServiceProvider extends ServiceProvider
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
