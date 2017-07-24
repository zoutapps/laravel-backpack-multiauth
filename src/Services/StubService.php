<?php

namespace ZoutApps\LaravelBackpackAuth\Services;

use ZoutApps\LaravelBackpackAuth\Traits\CanNormalizeString;

class StubService
{
    use CanNormalizeString;

    /**
     * Default singular keys.
     * @var array
     */
    private $singularKeys = [
        '{{singularCamel}}',
        '{{singularSlug}}',
        '{{singularSnake}}',
        '{{singularClass}}',
    ];

    /**
     * Default plural keys.
     * @var array
     */
    private $pluralKeys = [
        '{{pluralCamel}}',
        '{{pluralSlug}}',
        '{{pluralSnake}}',
        '{{pluralClass}}',
    ];
    private $classKey = '{{Class}}';
    private $modelSubKey = '{{modelSub}}';

    public function __construct()
    {
    }

    private function generateReplacements($replace)
    {
        return [
            camel_case($replace),
            str_slug($replace),
            snake_case($replace),
            ucfirst(camel_case($replace)),
        ];
    }

    /**
     * Replace default keys in subject with replace.
     *
     * @param string $replace the replacement value
     * @param string $subject the subject to replace in
     * @return string the subject with replacements
     */
    public function replace(string $replace, string $subject): string
    {
        $keys = (object) [
            'singular' => $this->singularKeys,
            'plural' => $this->pluralKeys,
        ];

        return $this->replaceKeys($replace, $subject, $keys);
    }

    /**
     * Replace class keyword.
     *
     * @param string $replace
     * @param string $subject
     * @return string
     */
    public function replaceClass(string $replace, string $subject): string
    {
        $replace = $this->normalize($replace);

        return str_replace($this->classKey, ucfirst(camel_case($replace)), $subject);
    }

    /**
     * Replace model subfolder/namespace in subject.
     *
     * @param string $subject the subject to replace in
     * @return string
     */
    public function replaceModelNamespace(string $subject): string
    {
        $modelSub = config('zoutapps.multiauth.model_path');
        if ($modelSub == null || $modelSub == '') {
            return str_replace($this->modelSubKey, '', $subject);
        }

        $replace = ltrim(rtrim($modelSub, '/'), '/');
        $replace = ltrim(rtrim($replace, '\\'), '\\');
        $replace = '\\'.$replace;

        return str_replace($this->modelSubKey, $replace, $subject);
    }

    /**
     * Generates the keys for given name.
     * @param $name
     * @return object
     */
    private function generateSuffixKeys($name)
    {
        $name = ucfirst($name);

        return (object) [
            'singular' => [
                '{{singularCamel'.$name.'}}',
                '{{singularSlug'.$name.'}}',
                '{{singularSnake'.$name.'}}',
                '{{singularClass'.$name.'}}',
            ],
            'plural' => [
                '{{pluralCamel'.$name.'}}',
                '{{pluralSlug'.$name.'}}',
                '{{pluralSnake'.$name.'}}',
                '{{pluralClass'.$name.'}}',
            ],
        ];
    }

    /**
     * @param string $replace the exact replacement value
     * @param string $subject the subject to replace in
     * @param string $key the key in {{<i>key</i>}}
     * @return string
     */
    public function replaceExact(string $replace, string $subject, string $key)
    {
        $key = '{{'.$key.'}}';

        return str_replace($key, $replace, $subject);
    }

    /**
     * Replace named keys.
     *
     * @param string $replace the replacement value
     * @param string $subject the subject to replace in
     * @param string $name the key suffix (in camelCase)
     * @return string the subject with applied replacements
     */
    public function replaceCustom(string $replace, string $subject, string $name)
    {
        $keys = $this->generateSuffixKeys($name);

        return $this->replaceKeys($replace, $subject, $keys);
    }

    private function replaceKeys(string $replace, string $subject, $keys)
    {
        $replace = $this->normalize($replace);
        $singularReplace = $this->generateReplacements(str_singular($replace));
        $pluralReplace = $this->generateReplacements(str_plural($replace));

        $subject = str_replace($keys->plural, $pluralReplace, $subject);
        $subject = str_replace($keys->singular, $singularReplace, $subject);

        return $subject;
    }
}
