<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class WorkerMessagingController extends Controller
{
    /**
     * Show messaging inbox
     */
    public function inbox(): View
    {
        $user = auth()->user();

        // Get all conversations for this worker (last message in each thread)
        $conversations = Message::where(function ($query) use ($user) {
                                    $query->where('sender_id', $user->id)
                                          ->orWhere('recipient_id', $user->id);
                                })
                                ->with('sender', 'recipient')
                                ->orderBy('created_at', 'desc')
                                ->get()
                                ->groupBy(function ($message) use ($user) {
                                    return $message->sender_id === $user->id 
                                        ? $message->recipient_id 
                                        : $message->sender_id;
                                })
                                ->map(function ($messages, $participantId) {
                                    return (object) [
                                        'id' => $participantId,
                                        'participant' => $messages->first()->sender_id === auth()->id() 
                                            ? $messages->first()->recipient 
                                            : $messages->first()->sender,
                                        'last_message' => $messages->first(),
                                        'messages' => $messages,
                                        'unread_count' => $messages->where('recipient_id', auth()->id())
                                                                  ->where('read_at', null)
                                                                  ->count(),
                                    ];
                                })
                                ->values();

        return view('worker.messages.inbox', [
            'conversations' => $conversations,
            'conversation' => null,
        ]);
    }

    /**
     * Show message thread with participant
     */
    public function show($participantId): View
    {
        $user = auth()->user();
        $participant = User::findOrFail($participantId);

        // Get all messages between user and participant
        $messages = Message::where(function ($query) use ($user, $participantId) {
                            $query->where('sender_id', $user->id)->where('recipient_id', $participantId)
                                  ->orWhere('sender_id', $participantId)->where('recipient_id', $user->id);
                        })
                        ->with('sender', 'recipient')
                        ->orderBy('created_at', 'asc')
                        ->get();

        // Mark as read
        Message::where('sender_id', $participantId)
               ->where('recipient_id', $user->id)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        return view('worker.messages.inbox', [
            'conversation' => (object) [
                'id' => $participantId,
                'participant' => $participant,
                'messages' => $messages,
            ],
        ]);
    }

    /**
     * Send message to recipient
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'body' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id'],
            'body' => $validated['body'],
        ]);

        // TODO: Send notification to recipient
        // TODO: Trigger real-time event for live message update

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Get unread message count
     */
    public function unreadCount(): JsonResponse
    {
        $count = Message::where('recipient_id', auth()->id())
                       ->whereNull('read_at')
                       ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId): JsonResponse
    {
        $message = Message::findOrFail($messageId);

        if ($message->recipient_id === auth()->id()) {
            $message->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete message
     */
    public function destroy($messageId): JsonResponse
    {
        $message = Message::findOrFail($messageId);

        // Only allow deletion if user is sender or recipient
        if ($message->sender_id !== auth()->id() && $message->recipient_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get new messages (for polling)
     */
    public function getNew(Request $request): JsonResponse
    {
        $since = $request->query('since', now()->subMinutes(5));

        $messages = Message::where('recipient_id', auth()->id())
                          ->where('created_at', '>', $since)
                          ->with('sender')
                          ->get();

        return response()->json(['messages' => $messages]);
    }
}
