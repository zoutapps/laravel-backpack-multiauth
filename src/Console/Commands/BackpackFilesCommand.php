<?php

namespace ZoutApps\LaravelBackpackMultiAuth\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\OverridesCanReplaceKeywords;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\OverridesGetArguments;
use ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits\ParsesServiceInput;


class BackpackFilesCommand extends FilesCommand
{
    use OverridesCanReplaceKeywords;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zoutapps:multiauth:backpack:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install multi-auth files';

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force override existing files'],
        ];
    }

    /**
     * Get the destination paths.
     *
     * @return array
     */
    public function getFiles()
    {
        $files = $this->getGeneralFiles();

        return $files;
    }

    /**
     * Get the default destination paths.
     *
     * @return array
     */
    public function getGeneralFiles()
    {
        $name = $this->getParsedNameInput();

        return [
            'routes'                      => [
                'path' => '/routes/' . mb_strtolower($name) . '.php',
                'stub' => __DIR__ . '/../stubs/routes/backpackroutes.stub',
            ],
            'middleware'                  => [
                'path' => '/app/Http/Middleware/RedirectIfNot' . ucfirst($name) . '.php',
                'stub' => __DIR__ . '/../stubs/Middleware/Middleware.stub',
            ],
            'guest_middleware'            => [
                'path' => '/app/Http/Middleware/RedirectIf' . ucfirst($name) . '.php',
                'stub' => __DIR__ . '/../stubs/Middleware/GuestMiddleware.stub',
            ],
            'login_controller'            => [
                'path' => '/app/Http/Controllers/' . ucfirst($name) . '/Auth/' . 'LoginController.php',
                'stub' => __DIR__ . '/../stubs/Controllers/BackpackLoginController.stub',
            ],
            'register_controller'         => [
                'path' => '/app/Http/Controllers/' . ucfirst($name) . '/Auth/' . 'RegisterController.php',
                'stub' => __DIR__ . '/../stubs/Controllers/BackpackRegisterController.stub',
            ],
            'forgot_password_controller'  => [
                'path' => '/app/Http/Controllers/' . ucfirst($name) . '/Auth/' . 'ForgotPasswordController.php',
                'stub' => __DIR__ . '/../stubs/Controllers/BackpackForgotPasswordController.stub',
            ],
            'reset_password_controller'   => [
                'path' => '/app/Http/Controllers/' . ucfirst($name) . '/Auth/' . 'ResetPasswordController.php',
                'stub' => __DIR__ . '/../stubs/Controllers/BackpackResetPasswordController.stub',
            ],
            'admin_controller' => [
                'path' => '/app/Http/Controllers/' . ucfirst($name) .'/AdminController.php',
                'stub' => __DIR__ . '/../stubs/Controllers/BackpackAdminController.stub',
            ],
            'reset_password_notification' => [
                'path' => '/app/Notifications/' . ucfirst($name) . 'ResetPassword.php',
                'stub' => __DIR__ . '/../stubs/Notifications/ResetPassword.stub',
            ],
        ];
    }
}
