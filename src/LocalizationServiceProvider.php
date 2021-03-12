<?php

namespace Cdz\Localization;

use Illuminate\Support\ServiceProvider;
use Cdz\Localization\Localization;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('localization', function($app) {
            return new Localization($app);
        });

        $this->registerHelpers();
        $this->registerMacros();
    }

    /**
     * Register helpers.
     *
     * @return void
     */
    public function registerHelpers()
    {
        require_once __DIR__ . '/Helpers/localization.php';
    }

    /**
     * Register macros.
     *
     * @return void
     */
    public function registerMacros()
    {
        require_once __DIR__.'/Macros/locales.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Console\InstallCommand::class];
    }
}
