<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Generators\ControllersGenerator;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;

class ControllersGeneratorTest extends GeneratorsTest
{

    /**
     * @var ControllersGenerator
     */
    protected $controllersGenerator;

    function setUp()
    {
        parent::setUp();

        $this->controllersGenerator = new ControllersGenerator(new FileService($this->filesystem), new StubService());
    }

    function test_generate_controllers()
    {
        $controllers = collect([
            'LoginController',
            'RegisterController',
            'ForgotPasswordController',
            'ResetPasswordController'
        ]);

        $controllers->each(function($controller) {
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

    function test_generate_controllers_not_overwriting_if_present_and_not_forced()
    {
        $controllers = collect([
            'LoginController',
            'RegisterController',
            'ForgotPasswordController',
            'ResetPasswordController'
        ]);

        $controllers->each(function($controller) {
            file_put_contents(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'), $controller);
            $this->assertFileExists(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'));
            $this->generatedFiles[] = base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php');
        });

        $created = $this->controllersGenerator->generateControllers('FooBar', false);
        $this->assertFalse($created);

        $controllers->each(function($controller) {
           $this->assertStringEqualsFile(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'), $controller);
        });
    }

    function test_generate_controllers_overwrites_if_present_and_forced()
    {
        $controllers = collect([
            'LoginController',
            'RegisterController',
            'ForgotPasswordController',
            'ResetPasswordController'
        ]);

        $controllers->each(function($controller) {
            file_put_contents(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'), $controller);
            $this->assertFileExists(base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php'));
            $this->generatedFiles[] = base_path('/app/Http/Controllers/FooBar/Auth/'.$controller.'.php');
        });

        $created = $this->controllersGenerator->generateControllers('FooBar', true);
        $this->assertTrue($created);

        $controllers->each(function($controller) {
            $fqn = '\App\Http\Controllers\FooBar\Auth\\'.$controller;
            $foobar = new $fqn();
            $this->assertInstanceOf($fqn, $foobar);
        });
    }

    //TODO: domain, lucid and service tests
}