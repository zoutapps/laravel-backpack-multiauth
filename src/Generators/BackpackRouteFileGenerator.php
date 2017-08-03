<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

class BackpackRouteFileGenerator extends RouteFileGenerator
{
    protected function stubPath(bool $lucid)
    {
        return __DIR__.'/../stubs/routes/backpackroutes.stub';
    }

    protected function getPath(string $name, bool $lucid, string $service = null)
    {
        return '/routes/'.$name.'.php';
    }
}
