<?php

use Illuminate\Support\Facades\Route;
use Teikun86\LaravelActivitylogUi\Http\Controllers\ActivityLogController;

Route::group([
    'domain' => config('activitylog-ui.route.domain'),
    'middleware' => config('activitylog-ui.route.middleware', ['web']),
], function () {
    Route::get(config('activitylog-ui.route.path', '/activity-logs'), [ActivityLogController::class, 'index'])
        ->name(config('activitylog-ui.route.name', 'activitylog-ui.index'));
});
