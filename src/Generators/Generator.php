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

    /**
     * @var \Illuminate\Console\Command
     */
    public $cmd;

    public function __construct(FileService $fileService, StubService $stubService)
    {
        $this->fileService = $fileService;
        $this->stubService = $stubService;
    }

    protected function filePath($path, SplFileInfo $file = null)
    {
        return base_path().$path;
    }

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

    protected function generateFiles(string $name, string $path, array $stubs, bool $force, string $service = null): bool
    {
        $state = true;
        foreach ($stubs as $stub) {
            $state = $state && $this->generateFile($name, $path, $stub, $force, $service);
        }

        return $state;
    }

    protected function generateFile(string $name, string $path, SplFileInfo $stub, bool $force, string $service = null)
    {
        $filePath = $this->filePath($path, $stub);

        $content = $this->compile($stub, $name, $service);

        return $this->fileService->putFile($filePath, $content, $force, $this->cmd);
    }
}
