<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MessagingController extends Controller
{
    /**
     * Get all messages for authenticated user
     */
    public function inbox()
    {
        $messages = Auth::user()->receivedMessages()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.inbox', ['messages' => $messages]);
    }

    /**
     * Get all sent messages
     */
    public function sent()
    {
        $messages = Auth::user()->sentMessages()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.sent', ['messages' => $messages]);
    }

    /**
     * Show a specific message
     */
    public function show(Message $message)
    {
        // Verify user can access this message
        if (Auth::user()->id !== $message->sender_id && Auth::user()->id !== $message->recipient_id) {
            abort(403, 'Unauthorized');
        }

        // Mark as read if recipient
        if (Auth::user()->id === $message->recipient_id && !$message->is_read) {
            $message->markAsRead();
        }

        return view('messages.show', ['message' => $message]);
    }

    /**
     * Send a new message
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'nullable|exists:users,id',
            'case_id' => 'nullable|exists:cases,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|min:5|max:5000',
            'message_type' => 'required|in:direct,broadcast',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // If broadcast, get admin user
            $recipientId = $request->input('message_type') === 'broadcast'
                ? User::where('is_admin', true)->first()?->id
                : $request->input('recipient_id');

            if (!$recipientId) {
                return response()->json(['error' => 'Recipient not found'], 404);
            }

            // Create the message
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $recipientId,
                'case_id' => $request->input('case_id'),
                'subject' => $request->input('subject'),
                'body' => $request->input('body'),
                'message_type' => $request->input('message_type'),
                'delivery_status' => 'pending',
                'is_encrypted' => true,
            ]);

            // Attempt to deliver message
            $this->deliverMessage($message);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'message_id' => $message->id,
                'delivery_status' => $message->delivery_status,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send message: ' . $e->getMessage());
            
            // Report to Sentry if configured
            if (function_exists('sentry_captureException')) {
                sentry_captureException($e);
            }

            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }

    /**
     * Deliver a message and update status
     */
    private function deliverMessage(Message $message)
    {
        try {
            // Simulate delivery process
            // In production, this would integrate with your email/notification service
            
            $message->markAsDelivered();

            Log::info('Message delivered', [
                'message_id' => $message->id,
                'sender_id' => $message->sender_id,
                'recipient_id' => $message->recipient_id,
            ]);
        } catch (\Exception $e) {
            $message->markAsFailed($e->getMessage());

            Log::error('Message delivery failed: ' . $e->getMessage(), [
                'message_id' => $message->id,
            ]);

            if (function_exists('sentry_captureException')) {
                sentry_captureException($e);
            }
        }
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        if (Auth::user()->id !== $message->recipient_id) {
            abort(403, 'Unauthorized');
        }

        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a message
     */
    public function destroy(Message $message)
    {
        if (Auth::user()->id === $message->sender_id) {
            $message->update(['deleted_by_sender' => true]);
        } elseif (Auth::user()->id === $message->recipient_id) {
            $message->update(['deleted_by_recipient' => true]);
        } else {
            abort(403, 'Unauthorized');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count
     */
    public function unreadCount()
    {
        $count = Auth::user()->receivedMessages()
            ->unread()
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Get delivery status of a message
     */
    public function deliveryStatus(Message $message)
    {
        if (Auth::user()->id !== $message->sender_id) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'delivery_status' => $message->delivery_status,
            'delivered_at' => $message->delivered_at,
            'delivery_error' => $message->delivery_error,
        ]);
    }
}
