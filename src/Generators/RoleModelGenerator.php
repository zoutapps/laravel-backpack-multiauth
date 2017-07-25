<?php

namespace ZoutApps\LaravelBackpackAuth\Generators;

class RoleModelGenerator extends ModelGenerator
{
    protected function stubPath($lucid = false)
    {
        if ($lucid) {
            return __DIR__.'/../stubs/Lucid/Model/RoleModel.stub';
        } else {
            return __DIR__.'/../stubs/Model/RoleModel.stub';
        }
    }
}
