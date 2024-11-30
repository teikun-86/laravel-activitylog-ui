<?php

namespace Nsd7\LaravelActivitylogUi\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = Activity::query()
            ->with('causer')
            ->when($request->search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%");
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
            ->latest()
            ->paginate(10);

        $models = Activity::select('subject_type')
            ->distinct()
            ->get()
            ->pluck('subject_type')
            ->map(fn ($type) => class_basename($type))
            ->toArray();

        $events = Activity::select('event')
            ->distinct()
            ->pluck('event')
            ->toArray();
        $logCreatedEvents = Activity::where('event', 'created')->count();
        $logUpdatedEvents = Activity::where('event', 'updated')->count();
        $logDeletedEvents = Activity::where('event', 'deleted')->count();
        $totalLogs = $logCreatedEvents + $logUpdatedEvents + $logDeletedEvents;

        return view('activitylog-ui::activity-log.index', compact('logs', 'models', 'events', 'logCreatedEvents', 'logUpdatedEvents', 'logDeletedEvents','totalLogs'));
    }
}
