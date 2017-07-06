<?php

namespace ZoutApps\LaravelBackpackAuth\Traits;


trait CanNormalizeString
{
    private function normalize(string $string) {
        return str_singular(trim($string));
    }
}