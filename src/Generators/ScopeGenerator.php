<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class ScopeGenerator extends Generator
{
    use CanNormalizeString;

    /**
     * @var string the role name
     */
    private $role;

    public function generateScope(string $name, bool $force, string $role)
    {
        $name = $this->normalize($name);
        $this->role = $role;
        $path = '/app/Scopes/'.ucfirst($name).'Scope.php';
        $stub = __DIR__.'/../stubs/Scopes/Scope.stub';

        return $this->generateFile($name, $path, new SplFileInfo($stub), $force);
    }

    protected function compile($file, $name, $service = null)
    {
        $content = parent::compile($file, $name, $service);

        return $this->stubService->replaceExact($this->role, $content, 'role');
    }
}
