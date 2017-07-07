<?php
/**
 * Created by PhpStorm.
 * User: Oli
 * Date: 07.07.17
 * Time: 15:57
 */

namespace ZoutApps\LaravelBackpackAuth\Commands;


use ZoutApps\LaravelBackpackAuth\Providers\BackpackGeneratorsProvider;
use ZoutApps\LaravelBackpackAuth\Providers\InjectorsProvider;

class BackpackRoleAuth extends AuthCommand
{
    protected $name = 'zoutapps:backpack:roleauth';
    protected $description = 'Swaps the default backpack auth model and guard with a newly created role based.';

    protected $attributes = ['name', 'role'];
    protected $options = ['force', 'domain', 'model'];

    public function __construct(BackpackGeneratorsProvider $generators, InjectorsProvider $injectors)
    {
        $this->availableAttributes['role'] = '{role : The name of the role to use}';
        parent::__construct($generators, $injectors);
    }

    public function handle()
    {
        if (!parent::handle()) {
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

        if (!$this->option('model')) {
            $this->generators->modelGenerator->generateModel($name, $force);
            //$this->generators->migrationsGenerator->generateMigrations($name, $force);
        }

        $this->injectors->routesInjector->appendWebRoutes($name, $force, $domain);

        return true;
    }
}