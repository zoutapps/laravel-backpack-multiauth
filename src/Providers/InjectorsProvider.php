<?php

namespace ZoutApps\LaravelBackpackAuth\Providers;


use ZoutApps\LaravelBackpackAuth\Injectors\AuthInjector;
use ZoutApps\LaravelBackpackAuth\Injectors\KernelInjector;
use ZoutApps\LaravelBackpackAuth\Injectors\RoutesInjector;

class InjectorsProvider
{
    /**
     * @var \ZoutApps\LaravelBackpackAuth\Injectors\AuthInjector
     */
    public $authInjector;

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Injectors\KernelInjector
     */
    public $kernelInjector;

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Injectors\Injector
     */
    public $routesInjector;


    function __construct(AuthInjector $authInjector, KernelInjector $kernelInjector, RoutesInjector $routesInjector)
    {
        $this->authInjector = $authInjector;
        $this->kernelInjector = $kernelInjector;
        $this->routesInjector = $routesInjector;
    }
}