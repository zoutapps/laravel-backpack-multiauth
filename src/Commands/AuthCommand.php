<?php

namespace ZoutApps\LaravelBackpackAuth\Commands;


use Illuminate\Console\Command;
use ZoutApps\LaravelBackpackAuth\Providers\GeneratorsProvider;
use ZoutApps\LaravelBackpackAuth\Providers\InjectorsProvider;

abstract class AuthCommand extends Command
{

    /**
     * @var array The attributes this command provides
     */
    protected $attributes = ['name', 'service'];

    /**
     * @var array The available command attributes
     */
    protected $availableAttributes = [
        'name'    => '{name : The name of your guard and model}',
        'service' => '{service? : The name of your lucid service}'
    ];

    /**
     * @var array The options this command provides
     */
    protected $options = ['force', 'domain', 'lucid', 'model', 'views', 'routes'];

    /**
     * @var array The command options
     */
    protected $availableOptions = [
        'force' => '{--f|force : Force overwriting existing files}',
        'domain' => '{--domain : Setup in subdomain}',
        'lucid' => '{--lucid : Use the lucid architecture (beta)}',
        'model' => '{--model : Exclude model and migration generation}',
        'views' => '{--views : Exclude view generation}',
        'routes' => '{--routes : Exclude routes setup (files will still be generated)}'
    ];

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Providers\GeneratorsProvider
     */
    protected $generators;

    /**
     * @var \ZoutApps\LaravelBackpackAuth\Providers\InjectorsProvider
     */
    protected $injectors;

    /**
     * AuthCommand constructor.
     * @param \ZoutApps\LaravelBackpackAuth\Providers\GeneratorsProvider $generators
     * @param \ZoutApps\LaravelBackpackAuth\Providers\InjectorsProvider $injectors
     */
    public function __construct(GeneratorsProvider $generators, InjectorsProvider $injectors)
    {
        $this->signature = $this->name;
        foreach ($this->attributes as $key) {
            $this->signature .= ' '. $this->availableAttributes[$key];
        }
        foreach ($this->options as $key) {
            $this->signature .= ' '.$this->availableOptions[$key];
        }
        parent::__construct();
        $this->generators = $generators;
        $this->injectors = $injectors;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->checkInput()) {
            $this->comment('exiting');
            return false;
        }
        return true;
    }

    protected function checkInput(): bool
    {
        if (!$this->option('force')) {
            $this->info('--force omitted. Will not overwrite existing files.');
            $this->error('At the moment you must provide te --force flag in order to generate anything.');
        }

        if (in_array('lucid', $this->options)) {
            if ($this->option('lucid') && !$this->argument('service')) {
                $this->error('Missing argument: <service>');
                $this->error('You need to pass a service name with the --lucid option.');
                return false;
            }
        }

        return true;
    }
}