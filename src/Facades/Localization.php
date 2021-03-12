<?php

namespace Cdz\Localization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cdz\Localization\Localization
 */
class Localization extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'localization';
    }
}
