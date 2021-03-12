<?php

namespace Cdz\Localization;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class Localization
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;

    /**
     * Localization constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Get config
     * @param string $key
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function config(string $key)
    {
        return config("localization.$key");
    }

    /**
     * Get locale attribute
     * @param string $key
     * @return array
     */
    public function locale(string $key = ''): ?string
    {
        $locale = $this->app->getLocale();
        $supported_locales = $this->config('supported_locales');

        if (isset($supported_locales[$locale])) {
            return ($key) ? $supported_locales[$locale][$key] : $locale;
        }
    }


    /**
     * @param string $name
     * @param array $params
     * @param bool $lang
     * @param string $lang
     * @return string|null
     */
    public function route(string $name, array $params=[], bool $absolute = true, string $lang = null) : ?string
    {
        $localesPattern = $this->getSupportedLocalesKeys()->implode('|');
        $name = preg_replace("/^($localesPattern)\./", '', $name);

        // Return localized route if exists
        $lang = $lang ?? $this->app->getLocale();
        if(Route::has("$lang.$name")) {
            return $this->app->url->route("$lang.$name", $params, $absolute);
        }

        // Return unlocalized route if exists
        if(Route::has("$name")) {
            return $this->app->url->route("$name", $params, $absolute);
        }

        return null;
    }

    /**
     * @param string $lang
     * @param bool $lang
     * @return string|null
     */
    public function currentRoute(string $lang = null, bool $absolute = true) : ?string
    {
        $request = $this->app->request;
        $route_name = $request->route()->getName();
        $parameters = $request->route()->parameters();
        $url = $this->route($route_name, $parameters, $absolute, $lang);

        if ($query = $request->query()) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function routeIs(string $name='') : bool
    {
        $request = $this->app->request;
        $localesPattern = $this->getSupportedLocalesKeys()->implode('|');
        $route_name = preg_replace("/^($localesPattern)\./", '', $request->route()->getName());
        return ($route_name == $name);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->app->getLocale();
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale = '')
    {
        $this->app->setLocale($locale);
    }

    /**
     * @return Collection
     */
    public function getSupportedLocales(): Collection
    {
        return collect($this->config('supported_locales'));
    }

    /**
     * @return Collection
     */
    public function getSupportedLocalesKeys(): Collection
    {
        return $this->getSupportedLocales()->keys();
    }

    /**
     * @param string $locale
     * @return bool
     */
    public function isSupportedLocale(string $locale = ''): bool
    {
        return $this->getSupportedLocales()->has($locale);
    }

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        $lang = $this->browser_language();
        return $lang ? $lang : $this->config('default_locale');
    }

    /**
     * @return string
     */
    public function browser_language() : string
    {
        $request = $this->app->request;
        $language_negociator = new LanguageNegotiator($this->getSupportedLocalesKeys()->toArray(), $request);
        return $language_negociator->negotiateLanguage();
    }
}
