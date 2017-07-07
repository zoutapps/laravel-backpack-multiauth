<?php

namespace ZoutApps\LaravelBackpackAuth\Commands;

class MultiAuth extends AuthCommand
{
    protected $name = 'zoutapps:multiauth';

    protected $description = 'Generates a new auth guard and sets everything up.';

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
        $service = $this->argument('service');
        $domain = $this->option('domain');
        $lucid = $this->option('lucid');
        $force = $this->option('force');

        $this->injectors->authInjector->injectAuth($name, $force, $lucid);
        $this->injectors->kernelInjector->injectKernel($name, $force, $lucid, $service);
        $this->injectors->routesInjector->injectRoutes($name, $force, $domain, $lucid, $service);

        $this->generators->routeFileGenerator->generateRoutesFile($name, $force, $lucid, $service);
        $this->generators->middlewaresGenerator->generateMiddlewares($name, $force, $domain, $lucid, $service);
        $this->generators->controllersGenerator->generateControllers($name, $force, $domain, $lucid, $service);
        $this->generators->notificationGenerator->generateNotification($name, $force, $domain, $lucid);

        if (! $this->option('model')) {
            $this->generators->modelGenerator->generateModel($name, $force, $lucid, $service);
            $this->generators->migrationsGenerator->generateMigrations($name, $force);
        }

        if (! $this->option('views')) {
            $this->generators->viewsGenerator->generateViews($name, $force, $domain, $lucid, $service);
        }

        if (! $this->option('routes')) {
            $this->injectors->routesInjector->appendWebRoutes($name, $force, $domain, $lucid, $service);
        }

        $this->info('Multi Auth with \''.ucfirst($name).'\'-guard successfully applied.');

        return true;
    }
}
