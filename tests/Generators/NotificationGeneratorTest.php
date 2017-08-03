<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Generators;

use Illuminate\Filesystem\Filesystem;
use ZoutApps\LaravelBackpackAuth\Services\FileService;
use ZoutApps\LaravelBackpackAuth\Services\StubService;
use ZoutApps\LaravelBackpackAuth\Generators\NotificationGenerator;

class NotificationGeneratorTest extends GeneratorsTest
{
    /**
     * @var NotificationGenerator
     */
    protected $notificationGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->notificationGenerator = new NotificationGenerator(new FileService(new Filesystem()), new StubService());
    }

    public function test_generate_notification()
    {
        $path = base_path('app/Notifications/FooBarResetPasswordNotification.php');
        if (file_exists($path)) {
            unlink($path);
        }

        $created = $this->notificationGenerator->generateNotification('FooBar', false);

        $this->assertTrue($created);

        $this->assertFileExists($path);
        $this->generatedFiles[] = $path;

        $fqn = '\App\Notifications\FooBarResetPasswordNotification';

        $foobar = new $fqn('some');
        $this->assertInstanceOf($fqn, $foobar);
    }
}
