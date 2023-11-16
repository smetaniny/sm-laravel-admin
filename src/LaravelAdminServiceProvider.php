<?php

namespace Smetaniny\SmLaravelAdmin;

use Illuminate\Support\ServiceProvider;

class LaravelAdminServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'smetaniny');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'smetaniny');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sm-laravel-admin.php', 'sm-laravel-admin');

        // Register the service the package provides.
        $this->app->singleton('sm-laravel-admin', function ($app) {
            return new LaravelAdmin;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sm-laravel-admin'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/sm-laravel-admin.php' => config_path('sm-laravel-admin.php'),
        ], 'sm-laravel-admin.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/smetaniny'),
        ], 'sm-laravel-admin.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/smetaniny'),
        ], 'sm-laravel-admin.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/smetaniny'),
        ], 'sm-laravel-admin.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}