<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class ModelGenerator extends Generator
{
    use CanNormalizeString;

    protected function modelPath($name, $lucid = false)
    {
        if ($lucid) {
            return $this->modelSubPath('/src/Data/', $name);
        } else {
            return $this->modelSubPath('/app/', $name);
        }
    }

    protected function stubPath($lucid = false)
    {
        if ($lucid) {
            return __DIR__.'/../stubs/Lucid/Model/Model.stub';
        } else {
            return __DIR__.'/../stubs/Model/Model.stub';
        }
    }

    public function generateModel(string $name, bool $force, bool $lucid = false, string $service = null)
    {
        if ($lucid) {
            return $this->generateLucidModel($name, $force, $service);
        }

        $name = $this->normalize($name);
        $path = $this->modelPath($name);
        $stub = $this->stubPath();

        return $this->generateFile($name, $path, new SplFileInfo($stub), $force);
    }

    private function generateLucidModel(string $name, bool $force, string $service)
    {
        $name = $this->normalize($name);
        $service = $this->normalize($service);
        $path = $this->modelPath($name, true);
        $stub = $this->stubPath(true);

        return $this->generateFile($name, $path, new SplFileInfo($stub), $force, $service);
    }

    private function modelSubPath($prefix, $name)
    {
        $sub = ltrim(config('zoutapps.multiauth.model_path'), '/');
        $path = rtrim($prefix.$sub.'/', '/');

        return $path.'/'.ucfirst($name).'.php';
    }
}
