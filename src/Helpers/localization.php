<?php

if (!function_exists('localization')) {

    /**
     * @return \Cdz\Localization\Localization
     */
    function localization()
    {
        return app('localization');
    }
}
