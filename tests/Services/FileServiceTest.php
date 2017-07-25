<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Services;

use Illuminate\Filesystem\Filesystem;
use ZoutApps\LaravelBackpackAuth\Test\LaravelTest;
use ZoutApps\LaravelBackpackAuth\Services\FileService;

class FileServiceTest extends LaravelTest
{
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var FileService
     */
    protected $fileService;

    protected $tempDir = __DIR__.'/../files/temp/';
    protected $dir = __DIR__.'/../files/';

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
        $this->fileService = new FileService(new Filesystem());
        if (! is_dir($this->dir)) {
            $this->filesystem->makeDirectory($this->dir, 0777, true);
        }
        if (! is_dir($this->tempDir)) {
            $this->filesystem->makeDirectory($this->tempDir, 0777, true);
        }
    }

    protected function tearDown()
    {
        $this->filesystem->deleteDirectory($this->dir);
        $this->filesystem->deleteDirectory($this->tempDir);
        parent::tearDown();
    }

    // put files

    public function test_put_file_will_create_file_in_specified_path()
    {
        $paths = [
            $this->tempDir.'temp.txt',
            $this->tempDir.'/subdir/sub/temp.txt',
        ];
        foreach ($paths as $path) {
            $this->fileService->putFile($path, '', false);
            $this->assertFileExists($path);
        }
    }

    public function test_put_file_will_not_overwrite_if_not_forced()
    {
        $path = $this->tempDir.'existing.txt';
        $this->filesystem->put($path, 'original content');
        $this->assertFileExists($path);
        $this->assertStringEqualsFile($path, 'original content');

        $this->fileService->putFile($path, 'new content', false);
        $this->assertStringEqualsFile($path, 'original content');
    }

    public function test_put_file_will_overwrite_if_forced()
    {
        $path = $this->tempDir.'/overwrite.txt';
        $this->filesystem->put($path, 'original content');

        $this->fileService->putFile($path, 'new content', true);
        $this->assertStringEqualsFile($path, 'new content');
    }

    public function test_append_file_appends_with_force()
    {
        $path = $this->tempDir.'/file.txt';
        file_put_contents($this->tempDir.'/file.txt', 'foo');
        $this->assertTrue($this->fileService->appendFile($path, 'bar', true));
        $this->assertFileExists($this->tempDir.'/file.txt');
        $this->assertStringEqualsFile($this->tempDir.'/file.txt', 'foobar');
    }

    public function test_append_file_does_not_appends_if_not_forced()
    {
        $path = $this->tempDir.'/file.txt';
        file_put_contents($this->tempDir.'/file.txt', 'foo');
        $this->assertFalse($this->fileService->appendFile($path, 'bar', false));
        $this->assertFileExists($this->tempDir.'/file.txt');
        $this->assertStringEqualsFile($this->tempDir.'/file.txt', 'foo');
    }

    public function test_get_content()
    {
        file_put_contents($this->tempDir.'/file.txt', 'Hello World');
        $this->assertEquals('Hello World', $this->fileService->getContent($this->tempDir.'/file.txt'));
    }

    public function test_content_exists()
    {
        $existpath = $this->tempDir.'/foo.txt';
        file_put_contents($existpath, 'foo');
        $this->assertTrue($this->fileService->contentExists($existpath, 'foo'));

        $notexistspath = $this->tempDir.'/bar.txt';
        file_put_contents($notexistspath, 'bar');
        $this->assertFalse($this->fileService->contentExists($notexistspath, 'foo'));
    }

    public function test_all_files_finds_files()
    {
        file_put_contents($this->tempDir.'/foo.txt', 'foo');
        file_put_contents($this->tempDir.'/bar.txt', 'bar');
        $allFiles = [];
        foreach ($this->fileService->allFiles($this->tempDir) as $file) {
            $allFiles[] = $file->getFilename();
        }
        $this->assertContains('foo.txt', $allFiles);
        $this->assertContains('bar.txt', $allFiles);
    }
}
