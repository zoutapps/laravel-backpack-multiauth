<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class ViewsGenerator extends Generator
{
    use CanNormalizeString;

    protected function filePath($path, SplFileInfo $file = null)
    {
        return base_path().$path.$file->getRelativePath().'/'.$this->parseFileName($file);
    }

    public function generateViews(string $name, bool $force, bool $domain = false, bool $lucid = false, string $service = null)
    {
        if ($lucid) {
            return $this->generateLucidViews($name, $force, $domain, $service);
        }

        $name = $this->normalize($name);
        $path = '/resources/views/'.$name.'/';
        $views = __DIR__.'/../stubs/views';

        $viewStubs = $this->fileService->allFiles($views);

        return $this->generateFiles($name, $path, $viewStubs, $views, $force);
    }

    private function generateLucidViews(string $name, bool $force, bool $domain, string $service)
    {
        $name = $this->normalize($name);
        $service = $this->normalize($service);

        $path = '/src/Services/'.studly_case($service).'/resources/views/'.$name.'/';
        $views = $domain ? __DIR__.'/../stubs/Lucid/views/' : __DIR__.'/../stubs/Lucid/domain-views/';
        $viewStubs = $this->fileService->allFiles($views);

        return $this->generateFiles($name, $path, $viewStubs, $force, $service);
    }

    private function parseFileName($file)
    {
        return $file->getBasename($file->getExtension()).'php';
    }
}
