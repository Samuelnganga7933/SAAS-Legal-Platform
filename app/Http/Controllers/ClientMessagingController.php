<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientMessagingController extends Controller
{
    /**
     * Show client messaging inbox
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

        return view('client.messaging.inbox', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Show a single message
     */
    public function show(Message $message)
    {
        // Check if user is the recipient
        if ($message->recipient_id !== Auth::id()) {
            return redirect()->route('client.messages.inbox')->with('error', 'Unauthorized access');
        }

        // Mark as read
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('client.messaging.show', ['message' => $message]);
    }

    /**
     * Show form to send message to admin
     */
    public function createMessage()
    {
        return view('client.messaging.create');
    }

    /**
     * Send message to admin
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'message_type' => 'required|in:text,video_call,phone_call,screenshot',
            'video_platform' => 'nullable|in:zoom,google_meet,teams',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
        ]);

        // Get the admin user
        $admin = \App\Models\User::where('is_admin', true)->first();

        if (!$admin) {
            return back()->withErrors(['admin' => 'Admin not found']);
        }

        $messageData = [
            'sender_id' => Auth::id(),
            'recipient_id' => $admin->id,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'message_type' => 'direct',
        ];

        // Handle different message types
        if ($validated['message_type'] === 'video_call') {
            $messageData['subject'] = '[VIDEO CALL REQUEST] ' . $messageData['subject'];
            $messageData['body'] = "Video Call Request via " . ucfirst(str_replace('_', ' ', $validated['video_platform'])) . "\n\n" . $messageData['body'];
        } elseif ($validated['message_type'] === 'phone_call') {
            $messageData['subject'] = '[PHONE CALL REQUEST] ' . $messageData['subject'];
            $messageData['body'] = "Phone Call Request\n\n" . $messageData['body'];
        } elseif ($validated['message_type'] === 'screenshot' && $request->hasFile('screenshot')) {
            $messageData['subject'] = '[WITH SCREENSHOT] ' . $messageData['subject'];
            // Store the screenshot
            $screenshotPath = $request->file('screenshot')->store('messages/screenshots', 'public');
            $messageData['body'] = $messageData['body'] . "\n\n[Screenshot attached: " . $screenshotPath . "]";
        }

        // Create message
        $message = \App\Models\Message::create($messageData);

        // Send email notification to admin
        try {
            \Mail::send('emails.message-notification', [
                'clientName' => Auth::user()->name,
                'clientEmail' => Auth::user()->email,
                'subject' => $message->subject,
                'body' => $message->body,
                'messageType' => $validated['message_type'],
            ], function ($mail) use ($admin) {
                $mail->to($admin->email)
                    ->subject('New Client Message: ' . request('subject'));
            });
        } catch (\Exception $e) {
            // Log error but don't fail the message creation
            \Log::error('Failed to send admin notification: ' . $e->getMessage());
        }

        return redirect()->route('client.messages.inbox')
            ->with('success', 'Message sent to admin successfully');
    }
}
