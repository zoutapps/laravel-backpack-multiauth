<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\ControllersGenerator;

class ControllersGeneratorTest extends GeneratorsTest
{
    /**
     * @var ControllersGenerator
     */
    protected $controllersGenerator;

    protected $controllers = [
        'LoginController',
        'RegisterController',
        'ForgotPasswordController',
        'ResetPasswordController',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->controllersGenerator = new ControllersGenerator(new FileService($this->filesystem), new StubService());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->filesystem->deleteDirectory(base_path('/app/Http/Controllers/FooBar/Auth/'));
    }

    public function test_generate_controllers()
    {
        $controllers = collect($this->controllers);

        $controllers->each(function ($controller) {
            if (file_exists(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'))) {
                unlink(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'));
            }
        });

        $created = $this->controllersGenerator->generateControllers('FooBar', false);
        $this->assertTrue($created);

        $controllers->each(function ($controller) {
            $this->assertFileExists(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'));
            $this->generatedFiles[] = base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php');

            $fqn = '\App\Http\Controllers\FooBar\Auth\\'.$controller;
            $foobar = new $fqn();
            $this->assertInstanceOf($fqn, $foobar);
        });
    }

    public function test_generate_controllers_not_overwriting_if_present_and_not_forced()
    {
        $controllers = collect($this->controllers);
        $path = base_path('/app/Http/Controllers/FooBar/Auth/');
        $this->filesystem->makeDirectory($path, 0755, true);

        $controllers->each(function ($controller) use ($path) {
            $this->filesystem->put($path.$controller.'.php', $controller);
            $this->assertFileExists($path.$controller.'.php');
            $this->generatedFiles[] = $path.$controller.'.php';
        });

        $created = $this->controllersGenerator->generateControllers('FooBar', false);
        $this->assertFalse($created);

        $controllers->each(function ($controller) {
            $this->assertStringEqualsFile(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'), $controller);
        });
    }

    public function test_generate_controllers_overwrites_if_present_and_forced()
    {
        $controllers = collect($this->controllers);
        $path = base_path('/app/Http/Controllers/FooBar/Auth/');
        $this->filesystem->makeDirectory($path, 0755, true);

        $controllers->each(function ($controller) use ($path) {
            $this->filesystem->put($path.$controller.'.php', $controller);
            $this->assertFileExists($path.$controller.'.php');
            $this->generatedFiles[] = $path.$controller.'.php';
        });

        $created = $this->controllersGenerator->generateControllers('FooBar', true);
        $this->assertTrue($created);

        $controllers->each(function ($controller) {
            $fqn = '\App\Http\Controllers\FooBar\Auth\\'.$controller;
            $foobar = new $fqn();
            $this->assertInstanceOf($fqn, $foobar);
        });
    }

    //TODO: domain, lucid and service tests
}
