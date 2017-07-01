<?php

namespace ZoutApps\LaravelBackpackMultiAuth\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\OverridesCanReplaceKeywords;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\OverridesGetArguments;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\ParsesServiceInput;


class ModelCommand extends InstallFilesCommand
{
    use OverridesCanReplaceKeywords, OverridesGetArguments, ParsesServiceInput;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zoutapps:multiauth:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Authenticatable Model';

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        $parentOptions = parent::getOptions();
        return array_merge($parentOptions, [
            ['lucid', false, InputOption::VALUE_NONE, 'Lucid architecture'],
        ]);
    }

    /**
     * Get the destination path.
     *
     * @return array
     */
    public function getFiles()
    {
        $name = $this->getParsedNameInput();
        $lucid = $this->option('lucid');

        return [
            'model' => [
                'path' => !$lucid
                    ? '/app/' . ucfirst($name) . '.php'
                    : '/src/Data/' . ucfirst($name) . '.php',
                'stub' => !$lucid
                    ? __DIR__ . '/../stubs/Model/Model.stub'
                    : __DIR__ . '/../stubs/Lucid/Model/Model.stub',
            ],
        ];
    }
}
