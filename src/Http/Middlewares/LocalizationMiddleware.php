<?php

namespace Cdz\Localization\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Cdz\Localization\Localization;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /** @var \Cdz\Localization\Localization */
    protected $localization;

    public function __construct(Localization $localization)
    {
        $this->localization = $localization;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1, '');

        if (!$this->localization->isSupportedLocale($locale)) {
            $locale = $this->localization->getDefaultLocale();
        }

        $this->localization->setLocale($locale);

        return $next($request);
    }
}
