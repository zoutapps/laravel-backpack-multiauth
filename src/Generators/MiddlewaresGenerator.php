<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use Illuminate\Support\Collection;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class MiddlewaresGenerator extends Generator
{
    use CanNormalizeString;

    protected function filePath($path, SplFileInfo $file = null)
    {
        $name = basename($path);
        $path = dirname($path).'/';
        $stubName = $file->getBasename('.stub');
        if ($stubName == 'Middleware') {
            return base_path().$path.'RedirectIf'.$name.'.php';
        } else {
            return base_path().$path.'RedirectIfNot'.$name.'.php';
        }
    }

    public function generateMiddlewares(
        string $name,
        bool $force,
        bool $domain = false,
        bool $lucid = false,
        string $service = null
    ) {
        $name = $this->normalize($name);
        $service = $this->normalize($service);
        $path = $this->getPath($lucid, $service).ucfirst($name);
        $stubs = $this->getStubs($lucid, $domain);

        return $this->generateFiles($name, $path, $stubs, $force);
    }

    protected function getPath(bool $lucid, string $service = null)
    {
        if ($lucid) {
            return '/src/Services/'.studly_case($service).'/Http/Middleware/';
        } else {
            return '/app/Http/Middleware/';
        }
    }

    private function getStubs(bool $lucid, bool $domain = false)
    {
        return Collection::make($this->getStubPaths($lucid, $domain))
            ->map(function ($stubPath) {
                return new SplFileInfo($stubPath);
            })->toArray();
    }

    private function getStubPaths(bool $lucid, bool $domain = false)
    {
        $prefix = $this->getPathPrefix($lucid, $domain);

        return [
            $prefix.'Middleware.stub',
            $prefix.'GuestMiddleware.stub',
        ];
    }

    private function getPathPrefix(bool $lucid, bool $domain = false)
    {
        if ($lucid && $domain) {
            return __DIR__.'/../stubs/Lucid/DomainMiddleware/';
        } elseif ($lucid) {
            return __DIR__.'/../stubs/Lucid/Middleware/';
        } else {
            return __DIR__.'/../stubs/Middleware/';
        }
    }
}
