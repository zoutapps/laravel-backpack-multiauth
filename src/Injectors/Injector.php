<?php

namespace ZoutApps\LaravelBackpackAuth\Injectors;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

abstract class Injector
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

    protected function compile(string $name, string $path, array $inject, string $service = null)
    {
        $original = $this->fileService->getContent($this->filePath($path));
        $content = $this->fileService->getContent($inject['stub']);
        $content = $this->stubService->replace($name, $content);
        if ($service != null) {
            $content = $this->stubService->replaceCustom($service, $content, 'service');
        }
        $content = $this->stubService->replaceModelNamespace($content);

        if (str_contains(trim($original), trim($content))) {
            return $original;
        }

        $stub = $inject['prefix'] ? $content.$inject['search'] : $inject['search'].$content;

        return str_replace($inject['search'], $stub, $original);
    }

    protected function injectFiles(string $name, string $path, array $injects, bool $force, string $service = null)
    {
        foreach ($injects as $inject) {
            $this->injectFile($name, $path, $inject, $force);
        }
    }

    protected function injectFile(string $name, string $path, array $inject, bool $force, string $service = null)
    {
        $filePath = $this->filePath($path);
        $content = $this->compile($name, $path, $inject, $service);

        return $this->fileService->putFile($filePath, $content, $force, $this->cmd);
    }
}
