<?php

namespace ZoutApps\LaravelBackpackAuth\Test\Services;

use ZoutApps\LaravelBackpackAuth\Test\LaravelTest;
use ZoutApps\LaravelBackpackAuth\Services\StubService;

class StubServiceTest extends LaravelTest
{
    /**
     * @var StubService
     */
    protected $stubService;

    public function setUp()
    {
        parent::setUp();

        $this->stubService = new StubService();
    }

    public function test_replace()
    {
        $keys = collect([
            '{{singularCamel}}',
            '{{singularSlug}}',
            '{{singularSnake}}',
            '{{singularClass}}',
            '{{pluralCamel}}',
            '{{pluralSlug}}',
            '{{pluralSnake}}',
            '{{pluralClass}}',
        ]);
        $subject = $keys->implode(' ');
        $expected = 'fooBarBaz foobarbaz foo_bar_baz FooBarBaz fooBarBazs foobarbazs foo_bar_bazs FooBarBazs';

        $testings = collect([
            'fooBarBaz',
            'FooBarBaz',
            'fooBarBazs',
            'FooBarBazs',
        ]);

        $testings->each(function ($input) use ($subject, $expected) {
            $replaced = $this->stubService->replace($input, $subject);
            $this->assertSame($expected, $replaced, 'Input of '.$input.' failed');
        });
    }

    public function test_replace_class()
    {
        $subject = '{{Class}}';

        $testings = collect([
            'thisIsAName',
            'ThisIsAName',
            "thisIsAName\n",
        ]);
        $testings->each(function ($name) use ($subject) {
            $replaced = $this->stubService->replaceClass($name, $subject);
            $this->assertSame('ThisIsAName', $replaced);
        });
    }

    public function test_replace_model_namespace()
    {
        $subject = '\App\Something{{modelSub}};';
        $testings = collect([
            null => '\App\Something;',
            '' => '\App\Something;',
            'SubNameSpace' => '\App\Something\SubNameSpace;',
            '/SubNameSpace' => '\App\Something\SubNameSpace;',
            'SubNameSpace/' => '\App\Something\SubNameSpace;',
            '/SubNameSpace/' => '\App\Something\SubNameSpace;',
            '\SubNameSpace' => '\App\Something\SubNameSpace;',
            'SubNameSpace\\' => '\App\Something\SubNameSpace;',
            '\SubNameSpace\\' => '\App\Something\SubNameSpace;',

        ]);

        $testings->each(function ($expected, $sub) use ($subject) {
            $this->app['config']->set('zoutapps.multiauth.model_path', $sub);
            $replaced = $this->stubService->replaceModelNamespace($subject);
            $this->assertSame($expected, $replaced, 'Input of '.$sub.' should result in '.$expected);
        });
    }

    public function test_replace_exact()
    {
        $subject = 'Foo{{key}}Baz';
        $replaced = $this->stubService->replaceExact('Bar', $subject, 'key');
        $this->assertSame('FooBarBaz', $replaced);
    }

    public function test_replace_custom()
    {
        $keys = collect([
            '{{singularCamelSomeKey}}',
            '{{singularSlugSomeKey}}',
            '{{singularSnakeSomeKey}}',
            '{{singularClassSomeKey}}',
            '{{pluralCamelSomeKey}}',
            '{{pluralSlugSomeKey}}',
            '{{pluralSnakeSomeKey}}',
            '{{pluralClassSomeKey}}',
        ]);
        $subject = $keys->implode(' ');
        $expected = 'fooBarBaz foobarbaz foo_bar_baz FooBarBaz fooBarBazs foobarbazs foo_bar_bazs FooBarBazs';

        $testings = collect([
            'fooBarBaz',
            'FooBarBaz',
            'fooBarBazs',
            'FooBarBazs',
        ]);

        $testings->each(function ($input) use ($subject, $expected) {
            $replaced = $this->stubService->replaceCustom($input, $subject, 'SomeKey');
            $this->assertSame($expected, $replaced, 'Input of '.$input.' failed');
        });
    }
}
