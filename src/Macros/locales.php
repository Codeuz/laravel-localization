<?php

use Illuminate\Support\Facades\Route;

if (!Route::hasMacro('locales')) {
    Route::macro('locales', function (Closure $closure) {
        $locales = localization()->getSupportedLocalesKeys();
        $currentLocale = localization()->getLocale();

        $locales->each(function ($locale) use ($closure) {
            localization()->setLocale($locale);
            $prefix = $locale;

            Route::as("$locale.")
                ->prefix($prefix)
                ->group($closure);
        });

        localization()->setLocale($currentLocale);
    });
}
