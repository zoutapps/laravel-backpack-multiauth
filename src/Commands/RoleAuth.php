<?php

namespace ZoutApps\LaravelBackpackAuth\Commands;

use ZoutApps\LaravelBackpackAuth\Providers\InjectorsProvider;
use ZoutApps\LaravelBackpackAuth\Providers\GeneratorsProvider;

class RoleAuth extends AuthCommand
{
    protected $name = 'zoutapps:roleauth';
    protected $description = 'Generates a user subclass with role and sets up corresponding guards.';

    protected $attributes = ['name', 'role'];
    protected $options = ['force', 'domain', 'model', 'views', 'routes'];

    public function __construct(GeneratorsProvider $generators, InjectorsProvider $injectors)
    {
        $this->availableAttributes['role'] = '{role : The name of the role to use}';
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
        $role = $this->argument('role');
        $domain = $this->option('domain');
        $force = $this->option('force');

        $this->injectors->authInjector->injectAuth($name, $force);
        $this->injectors->kernelInjector->injectKernel($name, $force);
        $this->injectors->routesInjector->injectRoutes($name, $force, $domain);

        $this->generators->routeFileGenerator->generateRoutesFile($name, $force);
        $this->generators->middlewaresGenerator->generateMiddlewares($name, $force, $domain);
        $this->generators->controllersGenerator->generateControllers($name, $force, $domain);
        $this->generators->notificationGenerator->generateNotification($name, $force, $domain);
        $this->generators->scopeGenerator->generateScope($name, $force, $role);

        if (! $this->option('model')) {
            $this->generators->modelGenerator->generateModel($name, $force);
            //$this->generators->migrationsGenerator->generateMigrations($name, $force);
        }

        if (! $this->option('views')) {
            $this->generators->viewsGenerator->generateViews($name, $force, $domain);
        }

        if (! $this->option('routes')) {
            $this->injectors->routesInjector->appendWebRoutes($name, $force, $domain);
        }

        $this->info('Role Auth with \''.ucfirst($name).'\' guard and role \''.$role.'\' successfully applied.');

        return true;
    }
}
