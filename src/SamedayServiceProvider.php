<?php

namespace Mchervenkov\Sameday;

use Illuminate\Support\ServiceProvider;
use Mchervenkov\Sameday\Commands\GetSamedayCounties;

class SamedayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/sameday.php' => config_path('sameday.php'),
            ], 'sameday-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'sameday-migrations');

            $this->publishes([
                __DIR__ . '/Models/' => app_path('Models'),
            ], 'sameday-models');

            $this->publishes([
                __DIR__ . '/Commands/' => app_path('Console/Commands'),
            ], 'sameday-commands');

            // Registering package commands.
            $this->commands([
                GetSamedayCounties::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('sameday', function () {
            return new Sameday();
        });
    }
}