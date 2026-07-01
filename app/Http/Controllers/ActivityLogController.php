<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request): View
    {
        Gate::authorize('activity_logs.view');

        $user  = auth()->user();
        $query = ActivityLog::with('user')->latest();

        // If the user only has "own" permission (not Super Admin), restrict to their logs
        $restrictToOwn = $user->can('activity_logs.own')
            && !$user->getRoleNames()->contains('Super Admin');

        if ($restrictToOwn) {
            $query->where('user_id', $user->id);
        }

        // Optional filter by user (only available when not restricted to own)
        if (!$restrictToOwn && $request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Optional filter by activity keyword
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('activity', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Optional date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->withQueryString();

        // Users for filter dropdown (hidden when restricted to own)
        $users = $restrictToOwn
            ? collect()
            : \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('activity_logs.index', compact('logs', 'users', 'restrictToOwn'));
    }
}
