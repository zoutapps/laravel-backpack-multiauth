<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\BackpackRouteFileGenerator;

class BackpackRouteFileGeneratorTest extends RouteFileGeneratorTest
{
    public function setUp()
    {
        parent::setUp();

        $this->routeFileGenerator = new BackpackRouteFileGenerator(new FileService($this->filesystem), new StubService());
    }
}
