<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

use SplFileInfo;
use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class MigrationsGenerator extends Generator
{
    use CanNormalizeString;

    public function generateMigrations(string $name, bool $force)
    {
        $name = $this->normalize($name);
        $path = base_path().'/database/migrations/';
        $stubs = $this->getStubs();

        return $this->generateFiles($name, $path, $stubs, $force);
    }

    protected function generateFile(string $name, string $path, SplFileInfo $stub, bool $force, string $service = null)
    {
        $files = $this->fileService->allFiles($path);
        $content = $this->compile($stub, $name);
        $migrationName = $this->getMigrationName($name, $stub);

        foreach ($files as $file) {
            if (str_contains($file->getFilename(), $migrationName)) {
                return $this->fileService->putFile($file->getPathname(), $content, $force, $this->cmd);
            }
        }

        $path = $path.date('Y_m_d_His').'_'.$migrationName;

        return $this->fileService->putFile($path, $content, $force, $this->cmd);
    }

    private function getMigrationName(string $name, SplFileInfo $stub)
    {
        if ($stub->getBasename('.stub') == 'migration') {
            return 'create_'.str_plural(snake_case($name)).'_table.php';
        } elseif ($stub->getBasename('.stub') == 'PasswordResetMigration') {
            return 'create_'.str_singular(snake_case($name)).'_password_resets_table.php';
        }
    }

    private function getStubs()
    {
        return [
            new SplFileInfo(__DIR__.'/../stubs/Model/migration.stub'),
            new SplFileInfo(__DIR__.'/../stubs/Model/PasswordResetMigration.stub'),
        ];
    }
}
