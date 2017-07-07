<?php

namespace ZoutApps\LaravelBackpackAuth\Providers;

use Illuminate\Console\Command;
use ZoutApps\LaravelBackpackAuth\Generators\ModelGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\ScopeGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\ViewsGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\RoleModelGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\RouteFileGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\MigrationsGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\ControllersGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\MiddlewaresGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\NotificationGenerator;

class GeneratorsProvider
{
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\ControllersGenerator
     */
    public $controllersGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\MiddlewaresGenerator
     */
    public $middlewaresGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\MigrationsGenerator
     */
    public $migrationsGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\ModelGenerator
     */
    public $modelGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\NotificationGenerator
     */
    public $notificationGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\RoleModelGenerator
     */
    public $roleModelGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\RouteFileGenerator
     */
    public $routeFileGenerator;
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\ViewsGenerator
     */
    public $viewsGenerator;

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Generators\ScopeGenerator
     */
    public $scopeGenerator;

    private $generators;

    public function __construct(
        ControllersGenerator $controllersGenerator,
        MiddlewaresGenerator $middlewaresGenerator,
        MigrationsGenerator $migrationsGenerator,
        ModelGenerator $modelGenerator,
        NotificationGenerator $notificationGenerator,
        RoleModelGenerator $roleModelGenerator,
        RouteFileGenerator $routeFileGenerator,
        ViewsGenerator $viewsGenerator,
        ScopeGenerator $scopeGenerator
    ) {
        $this->controllersGenerator = $controllersGenerator;
        $this->middlewaresGenerator = $middlewaresGenerator;
        $this->migrationsGenerator = $migrationsGenerator;
        $this->modelGenerator = $modelGenerator;
        $this->notificationGenerator = $notificationGenerator;
        $this->roleModelGenerator = $roleModelGenerator;
        $this->routeFileGenerator = $routeFileGenerator;
        $this->viewsGenerator = $viewsGenerator;
        $this->scopeGenerator = $scopeGenerator;

        $this->generators = [
            $this->controllersGenerator,
            $this->middlewaresGenerator,
            $this->migrationsGenerator,
            $this->modelGenerator,
            $this->notificationGenerator,
            $this->roleModelGenerator,
            $this->routeFileGenerator,
            $this->viewsGenerator,
            $this->scopeGenerator,
        ];
    }

    public function setCommand(Command $cmd)
    {
        foreach ($this->generators as $generator) {
            $generator->cmd = $cmd;
        }
    }
}
