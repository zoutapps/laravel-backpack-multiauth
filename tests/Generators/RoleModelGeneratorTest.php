<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use Illuminate\Filesystem\Filesystem;
use ZoutApps\LaravelBackpackAuth\Generators\RoleModelGenerator;
use ZoutApps\LaravelBackpackAuth\Generators\ScopeGenerator;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;

class RoleModelGeneratorTest extends ModelGeneratorTest
{
    public function setUp()
    {
        parent::setUp();

        $fileService = new FileService(new Filesystem());
        $stubService = new StubService();
        $this->modelGenerator = new RoleModelGenerator($fileService, $stubService);

        $content = '<?php namespace App\Models; class User extends \App\User {}';
        $fileService->putFile(base_path('/app/Models/User.php'), $content, true);
        $this->generatedFiles[] = base_path('/app/Models/User.php');

        $scopeGenerator = new ScopeGenerator($fileService, $stubService);
        $scopeGenerator->generateScope('FooBar', true, 'some_role');
    }
}