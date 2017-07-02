<?php

namespace ZoutApps\LaravelBackpackMultiAuth\Console\Commands;


use Illuminate\Support\Facades\Artisan;
use SplFileInfo;
use Symfony\Component\Console\Input\InputOption;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Helper\WriteFilesAndReplaceCommand;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\OverridesCanReplaceKeywords;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\OverridesGetArguments;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\ParsesServiceInput;

class MultiAuthCommand extends WriteFilesAndReplaceCommand
{

    use OverridesCanReplaceKeywords, OverridesGetArguments, ParsesServiceInput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'zoutapps:multiauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new auth guard for multiauth in Laravel';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->checkProvidedArgumentsAndOptions()) {
            return true;
        }

        $name = $this->getParsedNameInput();
        $domain = $this->option('domain');
        $lucid = $this->option('lucid');
        $service = $this->getParsedServiceInput() ?: null;

        $this->applySettings($name, $service, $domain, $lucid);
        $this->applyFiles($name, $service, $domain, $lucid);

        if (!$this->option('model')) {
            $this->applyModel($name, $lucid);
        }

        if (!$this->option('views')) {
            $this->applyViews($name, $service, $lucid);
        }

        if (!$this->option('routes')) {
            $this->installWebRoutes($service, $domain, $lucid);
        }

        $this->info('Multi Auth with ' . ucfirst($name) . ' guard successfully installed.');

        return true;
    }

    private function checkProvidedArgumentsAndOptions()
    {
        if ($this->option('lucid') && !$this->getParsedServiceInput()) {
            $this->error('You must pass a Service name with the `--lucid` option.');
            return false;
        }

        if (!$this->option('force')) {
            $this->info('Use `-f` flag first.');
            return false;
        }
        return true;
    }

    protected function applySettings($name, $service, $domain, $lucid)
    {
        Artisan::call('zoutapps:multiauth:settings', [
            'name'     => $name,
            'service'  => $service,
            '--domain' => $domain,
            '--lucid'  => $lucid,
            '--force'  => true
        ]);
    }

    protected function applyFiles($name, $service, $domain, $lucid)
    {
        Artisan::call('zoutapps:multiauth:files', [
            'name'     => $name,
            'service'  => $service,
            '--domain' => $domain,
            '--lucid'  => $lucid,
            '--force'  => true
        ]);
    }

    protected function applyModel($name, $lucid)
    {
        Artisan::call('zoutapps:multiauth:model', [
            'name'    => $name,
            '--lucid' => $lucid,
            '--force' => true
        ]);

        $this->installMigration();
        $this->installPasswordResetMigration();
    }

    protected function applyViews($name, $service, $lucid)
    {
        Artisan::call('zoutapps:multiauth:views', [
            'name'    => $name,
            'service' => $service,
            '--lucid' => $lucid,
            '--force' => true
        ]);
    }

    protected function installWebRoutes($service, $domain, $lucid)
    {

        if ($lucid) {
            $stub = !$domain
                ? __DIR__ . '/../stubs/Lucid/routes/web.stub'
                : __DIR__ . '/../stubs/Lucid/domain-routes/web.stub';

            $lucidPath = base_path() . '/src/Services/' . studly_case($service) . '/Http/routes.php';
            $lucidStub = !$domain
                ? __DIR__ . '/../stubs/Lucid/routes/map-method.stub'
                : __DIR__ . '/../stubs/Lucid/domain-routes/map-method.stub';

            if (!$this->contentExists($lucidPath, $lucidStub)) {
                $lucidFile = new SplFileInfo($lucidStub);
                $this->appendFile($lucidPath, $lucidFile);
            }

            if (!$this->contentExists($lucidPath, $stub)) {
                $file = new SplFileInfo($stub);
                $this->appendFile($lucidPath, $file);

                return true;
            }

            return false;
        }

        $path = base_path() . '/routes/web.php';
        $stub = __DIR__ . '/../stubs/routes/web.stub';
        if ($domain) {
            $stub = __DIR__ . '/../stubs/domain-routes/web.stub';
        }

        if (!$this->contentExists($path, $stub)) {
            $file = new SplFileInfo($stub);
            $this->appendFile($path, $file);

            return true;
        }

        return false;
    }

    /**
     * Install Migration.
     *
     * @return bool
     */
    public function installMigration()
    {
        $name = $this->getParsedNameInput();

        $migrationDir = base_path() . '/database/migrations/';
        $migrationName = 'create_' . str_plural(snake_case($name)) . '_table.php';
        $migrationStub = new SplFileInfo(__DIR__ . '/../stubs/Model/migration.stub');

        $files = $this->files->allFiles($migrationDir);

        foreach ($files as $file) {
            if (str_contains($file->getFilename(), $migrationName)) {
                $this->putFile($file->getPathname(), $migrationStub);

                return true;
            }
        }

        $path = $migrationDir . date('Y_m_d_His') . '_' . $migrationName;
        $this->putFile($path, $migrationStub);

        return true;
    }

    /**
     * Install PasswordResetMigration.
     *
     * @return bool
     */
    public function installPasswordResetMigration()
    {
        $name = $this->getParsedNameInput();

        $migrationDir = base_path() . '/database/migrations/';
        $migrationName = 'create_' . str_singular(snake_case($name)) . '_password_resets_table.php';
        $migrationStub = new SplFileInfo(__DIR__ . '/../stubs/Model/PasswordResetMigration.stub');

        $files = $this->files->allFiles($migrationDir);

        foreach ($files as $file) {
            if (str_contains($file->getFilename(), $migrationName)) {
                $this->putFile($file->getPathname(), $migrationStub);

                return true;
            }
        }

        $path = $migrationDir . date('Y_m_d_His', strtotime('+1 second')) . '_' . $migrationName;
        $this->putFile($path, $migrationStub);

        return true;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force override existing files'],
            ['domain', false, InputOption::VALUE_NONE, 'Install in a subdomain'],
            ['lucid', false, InputOption::VALUE_NONE, 'Lucid architecture'],
            ['model', null, InputOption::VALUE_NONE, 'Exclude model and migration'],
            ['views', null, InputOption::VALUE_NONE, 'Exclude views'],
            ['routes', null, InputOption::VALUE_NONE, 'Exclude routes'],
        ];
    }

}
