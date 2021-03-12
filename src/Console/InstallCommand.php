<?php

namespace Cdz\Localization\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdz-localization:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the CDZ Localization resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // NPM Packages
        $this->updateNodePackages(function ($packages) {
            return [
                    '@tailwindcss/forms' => '^0.2.1',
                    'alpinejs' => '^2.7.3',
                    'autoprefixer' => '^10.1.0',
                    'postcss' => '^8.2.1',
                    'postcss-import' => '^12.0.1',
                    'tailwindcss' => '^2.0.2',
                ] + $packages;
        });

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\Cdz\Localization\Http\Middlewares\LocalizationMiddleware::class');

        // Langs...
        (new Filesystem)->ensureDirectoryExists(resource_path('lang/de'));
        (new Filesystem)->ensureDirectoryExists(resource_path('lang/en'));
        (new Filesystem)->ensureDirectoryExists(resource_path('lang/fr'));
        (new Filesystem)->ensureDirectoryExists(resource_path('lang/it'));
        copy(__DIR__.'/../../stubs/resources/lang/de/welcome.php', resource_path('lang/de/welcome.php'));
        copy(__DIR__.'/../../stubs/resources/lang/en/welcome.php', resource_path('lang/en/welcome.php'));
        copy(__DIR__.'/../../stubs/resources/lang/fr/welcome.php', resource_path('lang/fr/welcome.php'));
        copy(__DIR__.'/../../stubs/resources/lang/it/welcome.php', resource_path('lang/it/welcome.php'));

        // Views...
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('views/components'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/components', resource_path('views/components'));

        copy(__DIR__.'/../../stubs/resources/views/welcome.blade.php', resource_path('views/welcome.blade.php'));

        // Components...
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/App/View/Components', app_path('View/Components'));

        // Config...
        copy(__DIR__.'/../../stubs/config/localization.php', base_path('config/localization.php'));

        // Routes...
        copy(__DIR__.'/../../stubs/routes/web.php', base_path('routes/web.php'));

        // Tailwind / Webpack...
        copy(__DIR__.'/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/resources/js/app.js', resource_path('js/app.js'));

        $this->info('CDZ Localization scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Install the middleware to a group in the application Http Kernel.
     *
     * @param  string  $after
     * @param  string  $name
     * @param  string  $group
     * @return void
     */
    protected function installMiddlewareAfter($after, $name, $group = 'web')
    {
        $httpKernel = file_get_contents(app_path('Http/Kernel.php'));

        $middlewareGroups = Str::before(Str::after($httpKernel, '$middlewareGroups = ['), '];');
        $middlewareGroup = Str::before(Str::after($middlewareGroups, "'$group' => ["), '],');

        if (! Str::contains($middlewareGroup, $name)) {
            $modifiedMiddlewareGroup = str_replace(
                $after.',',
                $after.','.PHP_EOL.'            '.$name.',',
                $middlewareGroup,
            );

            file_put_contents(app_path('Http/Kernel.php'), str_replace(
                $middlewareGroups,
                str_replace($middlewareGroup, $modifiedMiddlewareGroup, $middlewareGroups),
                $httpKernel
            ));
        }
    }
}
