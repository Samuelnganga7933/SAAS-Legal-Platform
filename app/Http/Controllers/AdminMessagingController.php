<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminMessagingController extends Controller
{
    /**
     * Show admin messaging inbox (messages from clients)
     */
    public function inbox()
    {
        $messages = Message::where('recipient_id', Auth::id())
            ->where('deleted_by_recipient', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Message::where('recipient_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('admin.messaging.inbox', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Show a single message from client
     */
    public function show(Message $message)
    {
        // Check if user is the recipient (admin)
        if ($message->recipient_id !== Auth::id()) {
            return redirect()->route('admin.messages.inbox')->with('error', 'Unauthorized access');
        }

        // Mark as read
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('admin.messaging.show', ['message' => $message]);
    }

    /**
     * Show form to send message to specific client
     */
    public function createDirectMessage()
    {
        $clients = User::where('is_admin', false)->orderBy('name')->get();
        return view('admin.messaging.create-direct', ['clients' => $clients]);
    }

    /**
     * Send direct message to client
     */
    public function sendDirectMessage(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'send_email' => 'boolean',
        ]);

        $client = User::findOrFail($validated['client_id']);

        // Create message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $client->id,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'message_type' => 'direct',
        ]);

        // Send email if requested
        if ($request->boolean('send_email')) {
            try {
                Mail::send('emails.admin-message-notification', [
                    'clientName' => $client->name,
                    'subject' => $message->subject,
                    'body' => $message->body,
                    'adminName' => Auth::user()->name,
                ], function ($mail) use ($client, $message) {
                    $mail->to($client->email)
                        ->subject('Message from Admin: ' . $message->subject);
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send client email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.messages.inbox')
            ->with('success', 'Message sent to ' . $client->name . ' successfully');
    }

    /**
     * Show form for broadcast message
     */
    public function createBroadcast()
    {
        return view('admin.messaging.create-broadcast');
    }

    /**
     * Send broadcast message to all clients
     */
    public function sendBroadcast(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'send_email' => 'boolean',
        ]);

        $clients = User::where('is_admin', false)->get();

        if ($clients->isEmpty()) {
            return back()->with('warning', 'No clients to broadcast to');
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($clients as $client) {
            try {
                // Create message
                $message = Message::create([
                    'sender_id' => Auth::id(),
                    'recipient_id' => $client->id,
                    'subject' => $validated['subject'],
                    'body' => $validated['body'],
                    'message_type' => 'broadcast',
                ]);

                // Send email if requested
                if ($request->boolean('send_email')) {
                    Mail::send('emails.broadcast-notification', [
                        'clientName' => $client->name,
                        'subject' => $message->subject,
                        'body' => $message->body,
                        'adminName' => Auth::user()->name,
                    ], function ($mail) use ($client, $message) {
                        $mail->to($client->email)
                            ->subject('Announcement: ' . $message->subject);
                    });
                }

                $successCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send broadcast to ' . $client->email . ': ' . $e->getMessage());
                $failureCount++;
            }
        }

        $message = "Broadcast sent to {$successCount} client(s)";
        if ($failureCount > 0) {
            $message .= " ({$failureCount} failed)";
        }

        return redirect()->route('admin.messages.broadcasts')
            ->with('success', $message);
    }

    /**
     * Show broadcast history
     */
    public function broadcastHistory()
    {
        $broadcasts = Message::where('sender_id', Auth::id())
            ->where('message_type', 'broadcast')
            ->where('deleted_by_sender', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.messaging.broadcasts', ['broadcasts' => $broadcasts]);
    }
}
