<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->get();
        return view('admin.notification.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->get();
        return view('admin.notification.create', compact('users'));
    }

    public function show($id)
    {
        $notification = Notification::with('user')->findOrFail($id);
        return view('admin.notification.show', compact('notification'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'type'    => 'required|string|max:100',
            'is_read' => 'nullable|in:0,1',
            'status'  => 'required|in:active,inactive',
        ]);

        Notification::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type'    => $request->type,
            'is_read' => $request->input('is_read', 0),
            'status'  => $request->status,
        ]);

        return redirect()->route('admin.notification.index')->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        $users = User::where('status', 'active')->get();
        return view('admin.notification.create', compact('notification', 'users'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'type'    => 'required|string|max:100',
            'is_read' => 'nullable|in:0,1',
            'status'  => 'required|in:active,inactive',
        ]);

        $notification->update([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type'    => $request->type,
            'is_read' => $request->input('is_read', 0),
            'status'  => $request->status,
        ]);

        return redirect()->route('admin.notification.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notification.index')->with('success', 'Notification deleted.');
    }
}
