<?php

namespace ZoutApps\LaravelBackpackAuth\Console\Commands\Traits;

trait OverridesCanReplaceKeywords
{
    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getParsedNameInput()
    {
        return str_singular($this->getNameInput());
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Replace names with pattern.
     *
     * @param $template
     * @return $this
     */
    public function replaceKeywords($template)
    {
        $name = $this->getParsedNameInput();
        $service = $this->getParsedServiceInput();

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

            '{{singularCamelService}}',
            '{{singularSlugService}}',
            '{{singularSnakeService}}',
            '{{singularClassService}}',
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

            camel_case($service),
            str_slug($service),
            snake_case($service),
            ucfirst(camel_case($service)),
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
