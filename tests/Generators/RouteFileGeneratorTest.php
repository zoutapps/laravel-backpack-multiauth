<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\RouteFileGenerator;

class RouteFileGeneratorTest extends GeneratorsTest
{
    /**
     * @var RouteFileGenerator
     */
    protected $routeFileGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->routeFileGenerator = new RouteFileGenerator(new FileService($this->filesystem), new StubService());
    }

    public function test_generate_route_file()
    {
        $path = base_path('routes/fooBar.php');
        if (file_exists($path)) {
            unlink($path);
        }

        $created = $this->routeFileGenerator->generateRoutesFile('FooBar', false);
        $this->assertTrue($created);

        $this->assertFileExists($path);
        $this->generatedFiles[] = $path;
    }

    public function test_generate_route_file_not_overwriting_if_present_and_not_forced()
    {
        $path = base_path('/routes/fooBar.php');
        $this->filesystem->put($path, 'foobar');
        $this->assertFileExists($path);
        $this->generatedFiles[] = $path;

        $created = $this->routeFileGenerator->generateRoutesFile('FooBar', false);
        $this->assertFalse($created);
        $this->assertStringEqualsFile($path, 'foobar');
    }

    public function test_generate_route_file_overwrites_if_present_and_forced()
    {
        $path = base_path('/routes/fooBar.php');
        $this->filesystem->put($path, 'foobar');
        $this->assertFileExists($path);
        $this->generatedFiles[] = $path;

        $created = $this->routeFileGenerator->generateRoutesFile('FooBar', true);
        $this->assertTrue($created);
        $this->assertStringNotEqualsFile($path, 'foobar');
    }
}
