<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class NotificationGenerator extends Generator
{
    use CanNormalizeString;

    protected function stubPath(bool $lucid, bool $domain)
    {
        if ($lucid && $domain) {
            return __DIR__.'/../stubs/Lucid/DomainNotifications/ResetPassword.stub';
        } elseif ($lucid) {
            return __DIR__.'/../stubs/Lucid/Notifications/ResetPassword.stub';
        } else {
            return __DIR__.'/../stubs/Notifications/ResetPassword.stub';
        }
    }

    public function generateNotification(string $name, bool $force, bool $domain = false, bool $lucid = false)
    {
        $name = $this->normalize($name);
        $path = $this->getPath($name, $lucid);
        $stub = $this->stubPath($lucid, $domain);

        return $this->generateFile($name, $path, new SplFileInfo($stub), $force);
    }

    private function getPath(string $name, bool $lucid)
    {
        if ($lucid) {
            return '/src/Domains/Notifications/'.ucfirst($name).'ResetPasswordNotification.php';
        } else {
            return '/app/Notifications/'.ucfirst($name).'ResetPasswordNotification.php';
        }
    }
}
