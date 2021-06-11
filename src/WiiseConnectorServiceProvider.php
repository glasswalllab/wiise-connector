<?php

namespace Glasswalllab\WiiseConnector;

use Illuminate\Support\ServiceProvider;
use glasswalllab\wiiseconnector\Providers\EventServiceProvider;

class WiiseConnectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wiiseConnector');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wiiseconnector');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('wiiseConnector.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/wiiseConnector'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/wiiseConnector'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/wiiseConnector'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/wiise.php', 'wiiseConnector');

        // Register the main class to use with the facade
        $this->app->singleton('wiiseConnector', function () {
            return new WiiseConnector;
        });

        $this->app->register(EventServiceProvider::class);
    }
}
