<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Generators\RouteFileGenerator;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;

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
        if (file_exists(base_path('/routes/fooBar.php'))) {
            unlink(base_path('/routes/fooBar.php'));
        }

        $created = $this->routeFileGenerator->generateRoutesFile('FooBar', false);
        $this->assertTrue($created);

        $this->assertFileExists(base_path('/routes/fooBar.php'));
        $this->generatedFiles[] = base_path('/routes/fooBar.php');
    }

    public function test_generate_route_file_not_overwriting_if_present_and_not_forced()
    {
        $path = base_path('/routes/fooBar.php');
        $this->filesystem->put($path, 'foobar');
        $this->assertFileExists($path);
        $this->generatedFiles[] = '/routes/fooBar.php';

        $created = $this->routeFileGenerator->generateRoutesFile('FooBar', false);
        $this->assertFalse($created);
        $this->assertStringEqualsFile($path, 'foobar');
    }

    public function test_generate_route_file_overwrites_if_present_and_forced()
    {
        $path = base_path('/routes/fooBar.php');
        $this->filesystem->put($path, 'foobar');
        $this->assertFileExists($path);
        $this->generatedFiles[] = '/routes/fooBar.php';

        $created = $this->routeFileGenerator->generateRoutesFile('FooBar', true);
        $this->assertTrue($created);
        $this->assertStringNotEqualsFile($path, 'foobar');
    }
}