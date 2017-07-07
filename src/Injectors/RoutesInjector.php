<?php

namespace ZoutApps\LaravelBackpackAuth\Injectors;

use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class RoutesInjector extends Injector
{
    use CanNormalizeString;

    public function injectRoutes(string $name, bool $force, bool $domain, bool $lucid = false, string $service = null)
    {
        $service = $this->normalize($service);
        $path = $this->getProviderPath($lucid, $service);
        $injects = $this->getInjects($lucid, $domain);

        $this->injectFiles($name, $path, $injects, $force, $service);
    }

    public function appendWebRoutes(string $name, bool $force, bool $domain, bool $lucid = false, string $service = null)
    {
        if ($lucid) {
            return $this->appendLucidWebRoutes($name, $force, $domain, $service);
        }

        $path = base_path().'/routes/web.php';
        $stub = __DIR__.'/../stubs/routes/web.stub';
        if ($domain) {
            $stub = __DIR__.'/../stubs/domain-routes/web.stub';
        }
        $stubContent = $this->fileService->getContent($stub);
        $stubContent = $this->stubService->replace($name, $stubContent);
        if (! $this->fileService->contentExists($path, $stubContent)) {
            return $this->fileService->appendFile($path, $stubContent, $force, $this->cmd);
        }

        return false;
    }

    private function appendLucidWebRoutes(string $name, bool $force, bool $domain, string $service = null)
    {
        $lucidPath = base_path().'/src/Services/'.studly_case($service).'/Http/routes.php';
        $stub = ! $domain
            ? __DIR__.'/../stubs/Lucid/routes/web.stub'
            : __DIR__.'/../stubs/Lucid/domain-routes/web.stub';

        $lucidStub = ! $domain
            ? __DIR__.'/../stubs/Lucid/routes/map-method.stub'
            : __DIR__.'/../stubs/Lucid/domain-routes/map-method.stub';

        $stubContent = $this->fileService->getContent($stub);
        $stubContent = $this->stubService->replace($name, $stubContent);
        $lucidContent = $this->fileService->getContent($lucidStub);
        $lucidContent = $this->stubService->replace($name, $lucidContent);

        if (! $this->fileService->contentExists($lucidPath, $lucidContent)) {
            if (! $this->fileService->appendFile($lucidPath, $lucidContent, $force, $this->cmd)) {
                return false;
            }
        }

        if (! $this->fileService->contentExists($lucidPath, $stubContent)) {
            return $this->fileService->appendFile($lucidPath, $stubContent, $force, $this->cmd);
        }

        return false;
    }

    private function getInjects(bool $lucid, bool $domain = false)
    {
        $register = $this->getRegister($lucid);
        $method = $this->getMethod($lucid, $domain);

        if ($method == null) {
            return [$register];
        } else {
            return [$register, $method];
        }
    }

    private function getRegister(bool $lucid)
    {
        if ($lucid) {
            return [
                'search' => '$this->loadRoutesFile($router, $namespace, $routesPath);',
                'stub'   => __DIR__.'/../stubs/Lucid/routes/map-register.stub',
                'prefix' => false,
            ];
        } else {
            return [
                'search' => '$this->mapWebRoutes();',
                'stub'   => __DIR__.'/../stubs/routes/map-register.stub',
                'prefix' => false,
            ];
        }
    }

    private function getMethod(bool $lucid, bool $domain = false)
    {
        if ($lucid) {
            return;
        } else {
            return [
                'search' => "    /**\n".'     * Define the "web" routes for the application.',
                'stub'   => $this->getMethodStub($domain),
                'prefix' => true,
            ];
        }
    }

    private function getMethodStub(bool $domain)
    {
        if ($domain) {
            return __DIR__.'/../stubs/domain-routes/map-method.stub';
        } else {
            return __DIR__.'/../stubs/routes/map-method.stub';
        }
    }

    private function getProviderPath(bool $lucid, string $service = null)
    {
        if ($lucid) {
            return '/src/Services/'.studly_case($service).'/Providers/RouteServiceProvider.php';
        } else {
            return '/app/Providers/RouteServiceProvider.php';
        }
    }
}
