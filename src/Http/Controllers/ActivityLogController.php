<?php

namespace Nsd7\LaravelActivitylogUi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;

if (class_exists("\\Illuminate\\Routing\\Controller")) {	
    class BaseController extends \Illuminate\Routing\Controller {}	
} elseif (class_exists("Laravel\\Lumen\\Routing\\Controller")) {	
    class BaseController extends \Laravel\Lumen\Routing\Controller {}	
}

class ActivityLogController extends BaseController
{
    public function index(Request $request)
    {
        $models = Cache::rememberForever('activity_models', function () {
            return Activity::select('subject_type')
                ->distinct()
                ->pluck('subject_type')
                ->map(fn($type) => class_basename($type))
                ->toArray();
        });

        $events = Cache::rememberForever('activity_events', function () {
            return Activity::select('event')->distinct()->pluck('event')->toArray();
        });


        $logs = Activity::query()
            ->select(['id', 'description', 'event', 'subject_type', 'subject_id', 'causer_id', 'properties', 'created_at']) // Specify needed columns
            ->with('causer:id,name')
            ->when($request->search, function ($query, $search) {
                $query->whereRaw("MATCH(description) AGAINST(? IN BOOLEAN MODE)", [$search]);
            })
            ->when($request->model, function ($query, $model) {
                $query->where('subject_type', 'like', "%{$model}%");
            })
            ->when($request->subject_id, function ($query, $subject_id) {
                $query->where('subject_id', $subject_id);
            })
            ->when($request->event, function ($query, $event) {
                $query->where('event', $event);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('activitylog-ui::activity-log.index', compact('logs', 'models', 'events'));
    }
}
