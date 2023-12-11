<?php

namespace Smetaniny\SmLaravelAdmin;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Smetaniny\smLaravelAdmin\Contracts\ResourceShowInterface;
use Smetaniny\smLaravelAdmin\Services\GetAllStrategy;
use Smetaniny\smLaravelAdmin\Services\GetFirstStrategy;
use Smetaniny\smLaravelAdmin\Services\ResourceShow;

class SmLaravelAdminServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        Route::middlewareGroup('smLaravelAdmin', config('smLaravelAdmin.middleware', []));

        $this->registerResources();

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'smLaravelAdmin');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->publishes([
            __DIR__ . '/../resources' => public_path('vendor/smetaniny/smLaravelAdmin'),
        ], ['smLaravelAdmin.assets']);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'smLaravelAdmin');

        // Регистрируем маршруты Nova, подключаемые в методе registerRoutes()
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sm-laravel-admin.php', 'smLaravelAdmin');

        // Register the service the package provides.
        $this->app->singleton('smLaravelAdmin', function () {
            return new smLaravelAdmin;
        });

        $this->app->singleton(ResourceShowInterface::class, ResourceShow::class);
        $this->app->tag([GetFirstStrategy::class, GetAllStrategy::class], 'QueryStrategyInterface');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['smLaravelAdmin'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/smetaniny/smLaravelAdmin'),
        ], ['smetaniny.smLaravelAdmin']);

        // Registering package commands.
        $this->commands([
            // Регистрация консольной команды для публикации ресурсов (Publish).
            Console\PublishCommand::class,
        ]);
    }
}

