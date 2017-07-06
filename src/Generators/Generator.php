<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

abstract class Generator
{
    use CanNormalizeString;

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Services\FileService
     */
    protected $fileService;

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Services\StubService
     */
    protected $stubService;

    function __construct(FileService $fileService, StubService $stubService)
    {
        $this->fileService = $fileService;
        $this->stubService = $stubService;
    }

    protected abstract function filePath($path, $file = null);

    protected function stubContent($file)
    {
        return $this->fileService->getContent($file->getPathName());
    }

    protected function compile($file, $name, $service = null)
    {
        $content = $this->stubContent($file);
        $content = $this->stubService->replace($name, $content);
        if ($service != null) {
            $content = $this->stubService->replaceCustom($service, $content, 'service');
        }
        $content = $this->stubService->replaceClass($name, $content);
        return $this->stubService->replaceModelNamespace($content);
    }

    protected function generateFiles(string $path, array $files, string $name, bool $force, string $service = null)
    {
        foreach ($files as $file) {
            $this->generateFile($name, $path, $file, $force, $service);
        }
    }

    protected function generateFile(string $name, string $path, SplFileInfo $file, bool $force, string $service = null)
    {
        $filePath = $this->filePath($path);
        $content = $this->compile($file, $name, $service);
        return $this->fileService->putFile($filePath, $content, $force);
    }
}