<?php

namespace ZoutApps\LaravelBackpackAuth\Services;

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

//    /**
//     * Install files method.
//     *
//     * @param $path
//     * @param $files
//     */
//    protected function writeFiles($path, $files)
//    {
//        foreach ($files as $file) {
//            $this->writeFile($path, $file);
//        }
//    }
//
//    protected function writeFile($path, $file)
//    {
//        $filePath = base_path() . $path . $file->getRelativePath() . '/' . $this->parseFilename($file);
//
//        if ($this->putFile($filePath, $file)) {
//            $this->getInfoMessage($filePath);
//        }
//    }

//    /**
//     * Parse filename
//     *
//     * @param $file
//     * @return string
//     */
//    protected function parseFilename($file)
//    {
//        return $this->getFileName($file) . $this->getExtension($file);
//    }

//    /**
//     * Get file name
//     *
//     * @param $file
//     * @return mixed
//     */
//    protected function getFileName($file)
//    {
//        return $this->getFileRealName($file);
//    }

//    /**
//     * Get file real name
//     *
//     * @param $file
//     * @return mixed
//     */
//    protected function getFileRealName($file)
//    {
//        return $file->getBasename($file->getExtension());
//    }

//    /**
//     * Get file extension.
//     *
//     * @param $file
//     * @return bool
//     */
//    protected function getExtension($file)
//    {
//        return $file->getExtension();
//    }

    /**
     * Put given file in path.
     *
     * @param string $path
     * @param string $content
     * @param bool $force
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function putFile($path, $content, $force): bool
    {
        if ($this->pathExists($path) && ! $force) {
            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $content);

        return true;
    }

    /**
     * Append given file in path.
     *
     * @param string $path
     * @param string $content
     * @param bool $force
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function appendFile(string $path, string $content, bool $force): bool
    {
        if ($this->pathExists($path) && ! $force) {
            return false;
        }

        $this->makeDirectory($path);
        $this->files->append($path, $content);

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
     * Put given content in path.
     *
     * @param string $path
     * @param string $content
     * @param bool $force
     * @return bool
     */
    public function putContent(string $path, string $content, bool $force): bool
    {
        if ($this->pathExists($path) && ! $force) {
            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $content);

        return true;
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
        //$content = $this->replaceKeywords($this->files->get($stub));

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

//    /**
//     * Compile content.
//     *
//     * @param $content
//     * @return mixed
//     */
//    protected function compile($content)
//    {
//        return $content;
//    }
}
