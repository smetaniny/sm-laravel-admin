<?php

namespace Smetaniny\SmLaravelAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class SmLaravelAdmin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sm-laravel-admin';
    }
}
