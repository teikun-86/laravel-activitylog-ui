<?php

namespace Nsd7\LaravelActivitylogUi;

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
    }
}
