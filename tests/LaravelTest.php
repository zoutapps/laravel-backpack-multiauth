<?php

namespace ZoutApps\LaravelBackpackAuth\Test;

use Illuminate\Foundation\Testing\TestCase;
use ZoutApps\LaravelBackpackAuth\AuthServiceProvider;

abstract class LaravelTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register(AuthServiceProvider::class);

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }
}
