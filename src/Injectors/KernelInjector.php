<?php

namespace ZoutApps\LaravelBackpackAuth\Injectors;

use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class KernelInjector extends Injector
{
    use CanNormalizeString;

    public function injectKernel(string $name, bool $force, bool $lucid = false, string $service = null)
    {
        $name = $this->normalize($name);
        $service = $this->normalize($service);
        $path = '/app/Http/Kernel.php';
        $inject = $this->getInject($lucid);

        $this->injectFile($name, $path, $inject, $force, $service);
    }

    private function getInject(bool $lucid)
    {
        return [
            'search' => 'protected $routeMiddleware = [',
            'stub'   => $this->kernelStubPath($lucid),
            'prefix' => false,
        ];
    }

    private function kernelStubPath(bool $lucid)
    {
        if ($lucid) {
            return __DIR__.'/../stubs/Lucid/Middleware/Kernel.stub';
        } else {
            return __DIR__.'/../stubs/Middleware/Kernel.stub';
        }
    }
}
