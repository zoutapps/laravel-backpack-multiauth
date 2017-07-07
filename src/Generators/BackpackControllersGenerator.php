<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;

class BackpackControllersGenerator extends ControllersGenerator
{

    public function generateControllers(
        string $name,
        bool $force,
        bool $domain = false,
        bool $lucid = false,
        string $service = null
    ) {
        parent::generateControllers($name, $force, $domain, $lucid, $service);

        $path = '/app/Http/Controllers/'.ucfirst($name).'/';
        $stub = $this->getPathPrefix($lucid, $domain).'AdminController.stub';
        $this->generateFile($name, $path, new SplFileInfo($stub), $force);

    }

    protected function getStubPaths(bool $lucid, bool $domain = false)
    {
        $paths = parent::getStubPaths($lucid, $domain);
        $paths[] = $this->getPathPrefix($lucid, $domain).'AdminController.stub';
        return $paths;
    }

    protected function getPathPrefix(bool $lucid, bool $domain = false)
    {
        return parent::getPathPrefix($lucid, $domain).'Backpack';
    }
}