<?php

namespace ZoutApps\LaravelBackpackAuth\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputOption;
use ZoutApps\LaravelBackpackAuth\Console\Commands\Traits\CanReplaceKeywords;

class BackpackAuthCommand extends MultiAuthCommand
{

    use CanReplaceKeywords;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'zoutapps:backpackauth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new guard for Laravel-Backpack admin panel login';


    public function handle()
    {
        if (!$this->option('force')) {
            $this->info('Use `-f` flag first.');
            return true;
        }

        $name = $this->getParsedNameInput();
        $domain = $this->option('domain');

        $this->applySettings($name, null, $domain, false);
        $this->applyFiles($name, null, $domain, false);
        if (!$this->option('model')) {
            $this->applyModel($name, false);
        }
        $this->installWebRoutes(null, $domain, false);

        $this->info('Multi Auth with ' . ucfirst($name) . ' guard successfully applied to backpack.');
        $this->info('You must apply some changes to your backpack config:');
        $this->info('* set your "user_model_fqn" to the created model ');
        $this->info('* disable "setup_auth_routes"');
        $this->info('* disable "setup_dashboard_routes"');
        $this->info('* set "route_prefix" to ' . str_singular(str_slug($name)) );

        // TODO: Apply to all possible backpack routes
        // permissionmanager, logmanager, settings etc.
    }

    protected function applyFiles($name, $service, $domain, $lucid)
    {
        Artisan::call('zoutapps:backpackauth:files', [
            'name'     => $name,
            'service'  => $service,
            '--force'  => true
        ]);
    }

    public function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force override existing files'],
            ['domain', false, InputOption::VALUE_NONE, 'Install in a subdomain'],
            //['lucid', false, InputOption::VALUE_NONE, 'Lucid architecture'],
            ['model', null, InputOption::VALUE_NONE, 'Exclude model and migration'],
        ];
    }
}