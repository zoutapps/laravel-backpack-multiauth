<?php

namespace ZoutApps\LaravelBackpackAuth\Traits;

trait CanNormalizeString
{
    private function normalize(string $string = null)
    {
        if ($string == null) {
            return null;
        }

        return str_singular(trim($string));
    }
}
