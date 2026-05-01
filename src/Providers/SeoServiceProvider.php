<?php

namespace Salehye\Seo\Providers;

use Illuminate\Support\ServiceProvider;
use Salehye\Seo\Commands\ClearCacheCommand;
use Salehye\Seo\Commands\InstallCommand;
use Salehye\Seo\Commands\SitemapGeneratorCommand;
use Salehye\Seo\Services\SeoService;
use Salehye\Seo\View\Components\Seo as SeoComponent;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../../config/seo.php', 'seo');

        // Bind SeoService to container
        $this->app->singleton('seo', function ($app) {
            return new SeoService;
        });

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                ClearCacheCommand::class,
                SitemapGeneratorCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../../config/seo.php' => config_path('seo.php'),
        ], 'seo-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'seo-migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../../resources/views/components' => resource_path('views/vendor/seo/components'),
        ], 'seo-views');

        // Publish all
        $this->publishes([
            __DIR__.'/../../config/seo.php' => config_path('seo.php'),
            __DIR__.'/../../database/migrations' => database_path('migrations'),
            __DIR__.'/../../resources/views/components' => resource_path('views/vendor/seo/components'),
        ], 'seo-all');

        // Register Blade component
        $this->loadViewsFrom(__DIR__.'/../../resources/views/components', 'seo');

        // Register anonymous Blade component
        $this->app->afterResolving('blade.compiler', function ($bladeCompiler) {
            $bladeCompiler->component('seo::components.seo', 'seo');
        });

        // Register class-based component
        $this->loadViewComponentsAs('seo', [
            SeoComponent::class,
        ]);

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['seo'];
    }
}
