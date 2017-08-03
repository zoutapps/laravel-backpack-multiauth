<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use Illuminate\Filesystem\Filesystem;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\ModelGenerator;

class ModelGeneratorTest extends GeneratorsTest
{
    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->modelGenerator = new ModelGenerator(new FileService(new Filesystem()), new StubService());
    }

    public function test_generate_basic_model_without_sub()
    {
        $this->app['config']->set('zoutapps.multiauth.model_path', '');
        if (file_exists(base_path('/app/FooBar.php'))) {
            unlink(base_path('/app/FooBar.php'));
        }

        $created = $this->modelGenerator->generateModel('FooBar', false);
        $this->assertTrue($created);

        $this->assertFileExists(base_path('/app/FooBar.php'));
        $this->generatedFiles[] = base_path('/app/FooBar.php');

        $fqn = '\App\FooBar';

        $foobar = new $fqn();
        $this->assertInstanceOf($fqn, $foobar);
    }

    public function test_generate_basic_model_with_sub()
    {
        $this->app['config']->set('zoutapps.multiauth.model_path', 'Models');
        if (file_exists(base_path('/app/Models/FooBar.php'))) {
            unlink(base_path('/app/Models/FooBar.php'));
        }

        $created = $this->modelGenerator->generateModel('FooBar', false);
        $this->assertTrue($created);

        $this->assertFileExists(base_path('/app/Models/FooBar.php'));
        $this->generatedFiles[] = base_path('/app/Models/FooBar.php');

        $fqn = '\App\Models\FooBar';

        $foobar = new $fqn();
        $this->assertInstanceOf($fqn, $foobar);
    }

    public function test_will_not_generate_if_exists_and_not_forced()
    {
        $this->app['config']->set('zoutapps.multiauth.model_path', '');
        file_put_contents(base_path('app/FooBar.php'), 'foobar');
        $path = base_path('/app/FooBar.php');
        $this->assertFileExists($path);
        $this->generatedFiles[] = $path;

        $created = $this->modelGenerator->generateModel('FooBar', false);
        $this->assertFalse($created);

        $this->assertStringEqualsFile($path, 'foobar');
    }

    public function test_will_overwrite_if_exists_and_forced()
    {
        $this->app['config']->set('zoutapps.multiauth.model_path', '');
        file_put_contents(base_path('app/FooBar.php'), 'foobar');
        $path = base_path('/app/FooBar.php');
        $this->assertFileExists($path);
        $this->generatedFiles[] = $path;

        $created = $this->modelGenerator->generateModel('FooBar', true);
        $this->assertTrue($created);

        $fqn = '\App\FooBar';

        $foobar = new $fqn();
        $this->assertInstanceOf($fqn, $foobar);
    }

    //TODO: tests with lucid and service
}
