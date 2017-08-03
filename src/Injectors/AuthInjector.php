<?php

namespace ZoutApps\LaravelBackpackAuth\Injectors;

use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class AuthInjector extends Injector
{
    use CanNormalizeString;

    public function injectAuth(string $name, bool $force, bool $lucid = false)
    {
        $name = $this->normalize($name);
        $path = '/config/auth.php';
        $injects = $this->getInjects($lucid);

        $this->injectFiles($name, $path, $injects, $force);
    }

    private function getInjects(bool $lucid)
    {
        return [
            'guard'     => [
                'search' => "'guards' => [",
                'stub'   => __DIR__.'/../stubs/config/guards.stub',
                'prefix' => false,
            ],
            'provider'  => [
                'search' => "'providers' => [",
                'stub'   => $this->providerStubPath($lucid),
                'prefix' => false,
            ],
            'passwords' => [
                'search' => "'passwords' => [",
                'stub'   => __DIR__.'/../stubs/config/passwords.stub',
                'prefix' => false,
            ],
        ];
    }

    private function providerStubPath(bool $lucid)
    {
        if ($lucid) {
            return __DIR__.'/../stubs/Lucid/config/providers.stub';
        } else {
            return __DIR__.'/../stubs/config/providers.stub';
        }
    }
}
