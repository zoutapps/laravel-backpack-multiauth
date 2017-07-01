<?php

namespace ZoutApps\LaravelBackpackMultiAuth;

use Illuminate\Support\ServiceProvider;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\FilesCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\ModelCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\MultiAuthCommand;
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
            SettingsCommand::class,
            FilesCommand::class,
            ModelCommand::class,
            ViewsCommand::class,
        ]);
    }
}
