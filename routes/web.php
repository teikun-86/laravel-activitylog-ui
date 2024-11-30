<?php

use Illuminate\Support\Facades\Route;
use Nsd7\LaravelActivitylogUi\Http\Controllers\ActivityLogController;

Route::middleware(['web', 'auth']) // Add middleware as needed
    ->prefix('admin/activity-log')
    ->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('activitylog-ui.index');
    });
