<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;


class BackpackControllersGenerator extends ControllersGenerator
{

    protected function getStubPaths(bool $lucid, bool $domain = false)
    {
        $paths = parent::getStubPaths($lucid, $domain);
        $paths[] = $this->getPathPrefix($lucid, $domain).'AdminController.stub';
    }

    protected function getPathPrefix(bool $lucid, bool $domain = false)
    {
        return parent::getPathPrefix($lucid, $domain).'Backpack';
    }
}