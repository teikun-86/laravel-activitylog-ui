<?php

namespace Teikun86\LaravelActivitylogUi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelActivitylogUiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish views
        $this->loadViewsFrom(__DIR__ . '/views', 'activitylog-ui');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function register()
    {
        // Register package resources or bindings if necessary
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'activitylog-ui');
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('activitylog-ui.php'),
        ], 'laravel-activitylog-ui-config');
    }
}
