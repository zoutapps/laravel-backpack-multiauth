<?php

namespace ZoutApps\LaravelBackpackAuth\Providers;


use ZoutApps\LaravelBackpackAuth\Generators\ControllersGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\MiddlewaresGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\MigrationsGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\ModelGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\NotificationGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\RoleModelGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\RouteFileGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\ViewsGenerator;

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


    function __construct(
        ControllersGenerator $controllersGenerator,
        MiddlewaresGenerator $middlewaresGenerator,
        MigrationsGenerator $migrationsGenerator,
        ModelGenerator $modelGenerator,
        NotificationGenerator $notificationGenerator,
        RoleModelGenerator $roleModelGenerator,
        RouteFileGenerator $routeFileGenerator,
        ViewsGenerator $viewsGenerator
    ) {
        $this->controllersGenerator = $controllersGenerator;
        $this->middlewaresGenerator = $middlewaresGenerator;
        $this->migrationsGenerator = $migrationsGenerator;
        $this->modelGenerator = $modelGenerator;
        $this->notificationGenerator = $notificationGenerator;
        $this->roleModelGenerator = $roleModelGenerator;
        $this->routeFileGenerator = $routeFileGenerator;
        $this->viewsGenerator = $viewsGenerator;
    }
}