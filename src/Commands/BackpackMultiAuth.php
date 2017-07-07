<?php

namespace ZoutApps\LaravelBackpackAuth\Commands;

use ZoutApps\LaravelBackpackAuth\Providers\InjectorsProvider;
use ZoutApps\LaravelBackpackAuth\Providers\BackpackGeneratorsProvider;

class BackpackMultiAuth extends AuthCommand
{
    protected $name = 'zoutapps:backpack:multiauth';
    protected $description = 'Swaps the default backpack auth model and guard with a newly created.';

    protected $attributes = ['name'];
    protected $options = ['force', 'domain', 'model'];

    public function __construct(BackpackGeneratorsProvider $generators, InjectorsProvider $injectors)
    {
        parent::__construct($generators, $injectors);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! parent::handle()) {
            return false;
        }

        $name = $this->argument('name');
        $domain = $this->option('domain');
        $force = $this->option('force');

        $this->injectors->authInjector->injectAuth($name, $force);
        $this->injectors->kernelInjector->injectKernel($name, $force);
        $this->injectors->routesInjector->injectRoutes($name, $force, $domain);

        $this->generators->routeFileGenerator->generateRoutesFile($name, $force);
        $this->generators->middlewaresGenerator->generateMiddlewares($name, $force, $domain);
        $this->generators->controllersGenerator->generateControllers($name, $force, $domain);
        $this->generators->notificationGenerator->generateNotification($name, $force, $domain);

        if (! $this->option('model')) {
            $this->generators->modelGenerator->generateModel($name, $force);
            $this->generators->migrationsGenerator->generateMigrations($name, $force);
        }

        $this->injectors->routesInjector->appendWebRoutes($name, $force, $domain);

        $this->info('Multi Auth with \''.ucfirst($name).'\'-guard successfully applied to backpack.');
        $this->warn('You need to be aware of some changes.');
        $this->info('> the config parameter \'user_model_fqn\' is no longer used.');
        $this->info('> you should disable \'setup_auth_routes\' as we created new ones and set them up.');
        $this->info('> you should disable \'setup_dashboard_routes\' as we created a new one.');
        $this->info('> when you use the \'route_prefix\' set it to \''.str_singular(str_slug($name)).'\'.');

        return true;
    }
}
