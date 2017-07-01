<?php

namespace ZoutApps\LaravelBackpackMultiAuth\Console\Commands\Traits;


trait CanReplaceKeywords
{
    /**
     * Replace keywords with pattern.
     *
     * @param $template
     * @return $this
     */
    public function replaceKeywords($template)
    {
        $name = $this->getParsedNameInput();

        $name = str_plural($name);

        $plural = [
            '{{pluralCamel}}',
            '{{pluralSlug}}',
            '{{pluralSnake}}',
            '{{pluralClass}}',
        ];

        $singular = [
            '{{singularCamel}}',
            '{{singularSlug}}',
            '{{singularSnake}}',
            '{{singularClass}}',
        ];

        $replacePlural = [
            camel_case($name),
            str_slug($name),
            snake_case($name),
            ucfirst(camel_case($name)),
        ];

        $replaceSingular = [
            str_singular(camel_case($name)),
            str_singular(str_slug($name)),
            str_singular(snake_case($name)),
            str_singular(ucfirst(camel_case($name))),
        ];



        $template = str_replace($plural, $replacePlural, $template);
        $template = str_replace($singular, $replaceSingular, $template);
        $template = str_replace('{{Class}}', ucfirst(camel_case($name)), $template);
        if (config('zoutapps.multiauth.model_path') != '') {
            $sub = ltrim(rtrim(config('zoutapps.multiauth.model_path'), '/'), '/');
            $template = str_replace('{{modelSub}}', '\\'.$sub, $template);
        } else {
            $template = str_replace('{{modelSub}}', '', $template);
        }

        return $template;
    }
}
