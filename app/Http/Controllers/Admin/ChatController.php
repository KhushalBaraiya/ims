<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display all chats.
     */
    public function index()
    {
        $chats = Chat::with('user')->latest()->get();
        return view('admin.chat.index', compact('chats'));
    }

    /**
     * Show the form to reply to a chat.
     */
    public function show($id)
    {
        $chat = Chat::with('user')->findOrFail($id);
        return view('admin.chat.show', compact('chat'));
    }

    /**
     * Show the form to create a new chat message (admin initiated).
     */
    public function create()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        return view('admin.chat.create', compact('users'));
    }

    /**
     * Store a new chat message (admin initiated).
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'user_message' => 'required|string|max:2000',
            'admin_reply'  => 'nullable|string|max:2000',
            'status'       => 'required|in:pending,replied,closed',
        ]);

        Chat::create([
            'user_id'      => $request->user_id,
            'user_message' => $request->user_message,
            'admin_reply'  => $request->admin_reply,
            'status'       => $request->status,
            'replied_at'   => $request->admin_reply ? now() : null,
        ]);

        return redirect()->route('admin.chat.index')
            ->with('success', 'Chat created successfully.');
    }

    /**
     * Show the form to edit / reply a chat.
     */
    public function edit($id)
    {
        $chat  = Chat::with('user')->findOrFail($id);
        $users = User::where('status', 'active')->orderBy('name')->get();
        return view('admin.chat.create', compact('chat', 'users'));
    }

    /**
     * Update (reply to) a chat message.
     */
    public function update(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'user_message' => 'required|string|max:2000',
            'admin_reply'  => 'nullable|string|max:2000',
            'status'       => 'required|in:pending,replied,closed',
        ]);

        $chat->update([
            'user_id'      => $request->user_id,
            'user_message' => $request->user_message,
            'admin_reply'  => $request->admin_reply,
            'status'       => $request->status,
            'replied_at'   => $request->admin_reply ? ($chat->replied_at ?? now()) : null,
        ]);

        return redirect()->route('admin.chat.index')
            ->with('success', 'Chat updated successfully.');
    }

    /**
     * Delete a chat.
     */
    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return redirect()->route('admin.chat.index')
            ->with('success', 'Chat deleted successfully.');
    }
}
