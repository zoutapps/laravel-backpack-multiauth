<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use Illuminate\Filesystem\Filesystem;
use ZoutApps\LaravelBackpackAuth\Test\LaravelTest;

abstract class GeneratorsTest extends LaravelTest
{
    protected $generatedFiles = [];

    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function setUp()
    {
        parent::setUp();
        $this->filesystem = new Filesystem();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->filesystem->delete($this->generatedFiles);
    }
}
