<?php

namespace ZoutApps\LaravelBackpackMultiAuth;

use Illuminate\Support\ServiceProvider;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\BackpackAuthCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\BackpackFilesCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\FilesCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\ModelCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\CreateMultiAuthCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\SettingsCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\ViewsCommand;

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
            CreateMultiAuthCommand::class,
            SettingsCommand::class,
            FilesCommand::class,
            ModelCommand::class,
            ViewsCommand::class,
            BackpackAuthCommand::class,
            BackpackFilesCommand::class
        ]);
    }
}
