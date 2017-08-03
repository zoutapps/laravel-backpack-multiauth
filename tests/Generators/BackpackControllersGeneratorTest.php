<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\BackpackControllersGenerator;

class BackpackControllersGeneratorTest extends ControllersGeneratorTest
{
    public function setUp()
    {
        parent::setUp();

        $this->controllersGenerator = new BackpackControllersGenerator(new FileService($this->filesystem), new StubService());
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->filesystem->deleteDirectory(base_path('/app/Http/Controllers/FooBar'));
    }

    public function test_generate_controllers()
    {
        if (file_exists(base_path('/app/Http/Controllers/FooBar/AdminController.php'))) {
            unlink(base_path('/app/Http/Controllers/FooBar/AdminController.php'));
        }

        parent::test_generate_controllers();

        $this->assertFileExists(base_path('/app/Http/Controllers/FooBar/AdminController.php'));
        $this->generatedFiles[] = base_path('/app/Http/Controllers/FooBar/AdminController.php');

        $fqn = '\App\Http\Controllers\FooBar\AdminController';
        $foobar = new $fqn();
        $this->assertInstanceOf($fqn, $foobar);
    }

    public function test_generate_controllers_not_overwriting_if_present_and_not_forced()
    {
        $path = base_path('/app/Http/Controllers/FooBar/');
        $this->filesystem->makeDirectory($path, 0755, true);
        $this->filesystem->put($path.'AdminController.php', 'AdminController');
        $this->assertFileExists($path.'AdminController.php');
        $this->generatedFiles[] = $path.'AdminController.php';

        parent::test_generate_controllers_not_overwriting_if_present_and_not_forced();

        $this->assertStringEqualsFile(base_path('app/Http/Controllers/FooBar/AdminController.php'), 'AdminController');
    }

    public function test_generate_controllers_overwrites_if_present_and_forced()
    {
        $path = base_path('/app/Http/Controllers/FooBar/');
        $this->filesystem->makeDirectory($path, 0755, true);
        $this->filesystem->put($path.'AdminController.php', 'AdminController');
        $this->assertFileExists($path.'AdminController.php');
        $this->generatedFiles[] = $path.'AdminController.php';

        parent::test_generate_controllers_overwrites_if_present_and_forced();

        $fqn = '\App\Http\Controllers\FooBar\AdminController';
        $foobar = new $fqn();
        $this->assertInstanceOf($fqn, $foobar);
    }
}
