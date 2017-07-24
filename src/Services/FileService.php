<?php

namespace ZoutApps\LaravelBackpackAuth\Services;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FileService
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    private function shouldNotOverwriteIfExists(string $path, bool $force, Command $cmd = null, string $question = null): bool
    {
        if (! $this->pathExists($path) || $force) {
            return false;
        }

        if ($cmd == null) {
            return true;
        }

        $question = $question ?? 'Do you want to overwrite it?';
        $cmd->warn('The file at '.$path.' exists.');

        return $cmd->confirm($question);
    }

    /**
     * Put given file in path.
     *
     * @param string $path
     * @param string $content
     * @param bool $force
     * @param \Illuminate\Console\Command $cmd
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function putFile($path, $content, $force, Command $cmd = null): bool
    {
        if ($this->shouldNotOverwriteIfExists($path, $force, $cmd)) {
            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $content);

        if ($cmd != null) {
            $cmd->comment('Wrote to file: '.$this->relativePath($path), 'v');
        }

        return true;
    }

    /**
     * Append given file in path.
     *
     * @param string $path
     * @param string $content
     * @param bool $force
     * @param Command $cmd
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function appendFile(string $path, string $content, bool $force, Command $cmd = null): bool
    {
        if ($this->shouldNotOverwriteIfExists($path, $force, $cmd, 'Do you want to append content?')) {
            return false;
        }

        $this->makeDirectory($path);
        $this->files->append($path, $content);

        if ($cmd != null) {
            $cmd->comment('Appended file: '.$this->relativePath($path), 'v');
        }

        return true;
    }

    /**
     * Get the file content.
     *
     * @param string $path
     * @return string
     */
    public function getContent(string $path)
    {
        return $this->files->get($path);
    }

    /**
     * Determine if file already exists.
     *
     * @param string $path
     * @return bool
     */
    protected function pathExists(string $path): bool
    {
        return $this->files->exists($path);
    }

    /**
     * Check if content exists in given file (path).
     *
     * @param string $path
     * @param string $content
     * @return bool
     */
    public function contentExists($path, $content)
    {
        $originalContent = $this->files->get($path);
        if (str_contains(trim($originalContent), trim($content))) {
            return true;
        }

        return false;
    }

    /**
     * Build the directory for if necessary.
     *
     * @param  string $path
     * @return bool
     */
    protected function makeDirectory(string $path): bool
    {
        if (! $this->files->isDirectory(dirname($path))) {
            return $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return true;
    }

    /**
     * Return array of all files in given path.
     *
     * @param string $path
     * @return array
     */
    public function allFiles(string $path)
    {
        return $this->files->allFiles($path);
    }

    private function relativePath(string $path)
    {
        $prefix = base_path();
        if (substr($path, 0, strlen($prefix)) == $prefix) {
            return substr($path, strlen($prefix));
        }

        return $path;
    }
}
