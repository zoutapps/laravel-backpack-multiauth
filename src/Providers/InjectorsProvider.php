<?php

namespace ZoutApps\LaravelBackpackAuth\Providers;

use Illuminate\Console\Command;
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

    private $injectors;

    public function __construct(AuthInjector $authInjector, KernelInjector $kernelInjector, RoutesInjector $routesInjector)
    {
        $this->authInjector = $authInjector;
        $this->kernelInjector = $kernelInjector;
        $this->routesInjector = $routesInjector;

        $this->injectors = [
            $this->authInjector,
            $this->kernelInjector,
            $this->routesInjector,
        ];
    }

    public function setCommand(Command $cmd)
    {
        foreach ($this->injectors as $injector) {
            $injector->cmd = $cmd;
        }
    }
}
