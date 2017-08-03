<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\MiddlewaresGenerator;

class MiddlewaresGeneratorTest extends GeneratorsTest
{
    /**
     * @var MiddlewaresGenerator
     */
    protected $middlewaresGenerator;

    protected $middlewares = [
        'RedirectIf',
        'RedirectIfNot',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->middlewaresGenerator = new MiddlewaresGenerator(new FileService($this->filesystem), new StubService());
    }

    public function test_generate_middlewares()
    {
        $path = base_path('/app/Http/Middleware/');
        $middlewares = collect($this->middlewares);
        $middlewares->each(function ($middleware) use ($path) {
            if (file_exists($path.$middleware.'FooBar.php')) {
                unlink($path.$middleware.'FooBar.php');
            }
        });

        $created = $this->middlewaresGenerator->generateMiddlewares('FooBar', false);
        $this->assertTrue($created);

        $middlewares->each(function ($middleware) use ($path) {
            $this->assertFileExists($path.$middleware.'FooBar.php');
            $this->generatedFiles[] = $path.$middleware.'FooBar.php';

            $fqn = '\App\Http\Middleware\\'.$middleware.'FooBar';
            $foobar = new $fqn();
            $this->assertInstanceOf($fqn, $foobar);
        });
    }
}
