<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use Illuminate\Support\Collection;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class ControllersGenerator extends Generator
{
    use CanNormalizeString;

    protected function filePath($path, SplFileInfo $file = null)
    {
        return parent::filePath($path, $file).$file->getBasename('.stub').'.php';
    }

    public function generateControllers(
        string $name,
        bool $force,
        bool $domain = false,
        bool $lucid = false,
        string $service = null
    ) {
        $name = $this->normalize($name);
        $service = $this->normalize($service);
        $path = $this->getPath($name, $lucid, $service);
        $stubs = $this->getStubs($lucid, $domain);

        return $this->generateFiles($name, $path, $stubs, $force);
    }

    protected function getPath(string $name, bool $lucid, string $service = null)
    {
        if ($lucid) {
            return '/src/Services/'.studly_case($service).'/Http/Controllers/'.ucfirst($name).'/Auth/';
        } else {
            return '/app/Http/Controllers/'.ucfirst($name).'/Auth/';
        }
    }

    private function getStubs(bool $lucid, bool $domain = false)
    {
        return Collection::make($this->getStubPaths($lucid, $domain))
            ->map(function ($stubPath) {
                return new SplFileInfo($stubPath);
            })->toArray();
    }

    protected function getStubPaths(bool $lucid, bool $domain = false)
    {
        $prefix = $this->getPathPrefix($lucid, $domain);

        return [
            $prefix.'LoginController.stub',
            $prefix.'RegisterController.stub',
            $prefix.'ForgotPasswordController.stub',
            $prefix.'ResetPasswordController.stub',
        ];
    }

    protected function getPathPrefix(bool $lucid, bool $domain = false)
    {
        if ($lucid && $domain) {
            return __DIR__.'/../stubs/Lucid/DomainControllers/';
        } elseif ($lucid) {
            return __DIR__.'/../stubs/Lucid/Controllers/';
        } else {
            return __DIR__.'/../stubs/Controllers/';
        }
    }
}
