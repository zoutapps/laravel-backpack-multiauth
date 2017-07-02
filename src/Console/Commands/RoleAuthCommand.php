<?php

namespace ZoutApps\LaravelBackpackAuth\Console\Commands;

use SplFileInfo;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Traits\CanReplaceKeywords;

class RoleAuthCommand extends MultiAuthCommand
{
    use CanReplaceKeywords;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'zoutapps:roleauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new User subclass model with global role scope to mimic MultiAuth with Roles from Spatie\\Permission';

    public function handle()
    {
        if (! $this->option('force')) {
            $this->info('Use `-f` flag first.');

            return true;
        }

        $name = $this->getParsedNameInput();
        $domain = $this->option('domain');

        $this->applySettings($name, null, $domain, false);
        $this->applyFiles($name, null, $domain, false);
        $this->applyScope($name);

        if (! $this->option('model')) {
            $this->applyModel($name, false);
        }

        if (! $this->option('views')) {
            $this->applyViews($name, null, false);
        }

        if (! $this->option('routes')) {
            $this->installWebRoutes(null, $domain, false);
        }

        $this->info('Role Auth with '.ucfirst($name).' guard successfully applied');
    }

    public function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['role', InputArgument::REQUIRED, 'The role to use'],
            ['service', InputArgument::OPTIONAL, 'The name of the service'],
        ];
    }

    protected function applyFiles($name, $service, $domain, $lucid)
    {
        Artisan::call('zoutapps:multiauth:files', [
            'name'    => $name,
            'service' => $service,
            '--force' => true,
        ]);
    }

    protected function applyScope($name)
    {
        $file = [
            'path' => '/app/Scopes/'.ucfirst($name).'Scope.php',
            'stub' => __DIR__.'/../stubs/Scopes/Scope.stub',
        ];

        $fullPath = base_path().$file['path'];
        $fileObject = new SplFileInfo($file['stub']);

        if ($this->putFile($fullPath, $fileObject)) {
            $this->getInfoMessage($fullPath);
        }
    }

    protected function applyModel($name, $lucid)
    {
        Artisan::call('zoutapps:roleauth:model', [
            'name'    => $name,
            '--lucid' => $lucid,
            '--force' => true,
        ]);
    }

    protected function compile($content)
    {
        $role = trim($this->argument('role'));
        $content = parent::compile($content);

        return str_replace('{{role}}', $role, $content);
    }

    public function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force override existing files'],
            ['domain', false, InputOption::VALUE_NONE, 'Install in a subdomain'],
            //['lucid', false, InputOption::VALUE_NONE, 'Lucid architecture'],
            ['model', null, InputOption::VALUE_NONE, 'Exclude model and migration'],
            ['views', null, InputOption::VALUE_NONE, 'Exclude views'],
            ['routes', null, InputOption::VALUE_NONE, 'Exclude routes'],
        ];
    }
}
