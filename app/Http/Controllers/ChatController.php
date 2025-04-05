<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show chat page with contacts and messages.
     */
    public function showChat(Request $request)
    {
        $authId = Auth::id();

        // Fetch contacts (users who have chatted with the authenticated user)
        $contacts = User::whereHas('sentMessages', function ($query) use ($authId) {
                $query->where('to_user_id', $authId);
            })
            ->orWhereHas('receivedMessages', function ($query) use ($authId) {
                $query->where('from_user_id', $authId);
            })
            ->get();

        // Selected chat
        $selectedUser = null;
        $messages = collect();

        if ($request->has('chat_with')) {
            $selectedUser = User::find($request->chat_with);
            if ($selectedUser) {
                $messages = Chat::where(function ($query) use ($authId, $selectedUser) {
                        $query->where('from_user_id', $authId)->where('to_user_id', $selectedUser->id);
                    })
                    ->orWhere(function ($query) use ($authId, $selectedUser) {
                        $query->where('from_user_id', $selectedUser->id)->where('to_user_id', $authId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('chat.chat', compact('contacts', 'selectedUser', 'messages'));
    }

    /**
     * Store a new chat message.
     */
    public function storeMessage(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        Chat::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.index', ['chat_with' => $request->to_user_id]);
    }

    /**
     * Search users to chat with.
     */
    public function searchUsers(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->search . '%')
            ->where('id', '!=', Auth::id()) // Exclude self
            ->get();

        return response()->json($users);
    }

    /**
     * Fetch messages between the authenticated user and another user.
     */
    public function fetchMessages($userId)
    {
        $authId = Auth::id();
        $messages = Chat::where(function ($query) use ($authId, $userId) {
                $query->where('from_user_id', $authId)->where('to_user_id', $userId);
            })
            ->orWhere(function ($query) use ($authId, $userId) {
                $query->where('from_user_id', $userId)->where('to_user_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
