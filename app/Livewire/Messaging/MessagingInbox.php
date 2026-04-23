<?php

namespace App\Livewire\Messaging;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessagingInbox extends Component
{
    public $search = '';
    public $selectedConversationPartnerId = null;
    public $newMessage = '';
    public $showNewConversationForm = false;
    public $recipientSearch = '';
    public $selectedRecipient = null;
    
    // Broadcast messaging properties
    public $showBroadcastForm = false;
    public $broadcastMessage = '';
    public $broadcastTarget = 'all'; // 'all' or 'subscribed'

    /**
     * Select a conversation by partner user ID
     */
    public function selectConversation($partnerId)
    {
        $this->selectedConversationPartnerId = $partnerId;
        
        // Mark all unread messages from this partner as read
        Message::where('sender_id', $partnerId)
            ->where('recipient_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    /**
     * Send a message to the selected conversation partner
     */
    public function sendMessage()
    {
        if (!$this->newMessage || !$this->selectedConversationPartnerId) {
            return;
        }

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $this->selectedConversationPartnerId,
            'subject' => 'Message',
            'body' => $this->newMessage,
            'message_type' => 'direct',
            'delivery_status' => 'delivered',
            'delivered_at' => now(),
        ]);

        $this->newMessage = '';
        $this->dispatch('messageSent');
    }

    public function toggleNewConversationForm()
    {
        $this->showNewConversationForm = !$this->showNewConversationForm;
        $this->recipientSearch = '';
        $this->selectedRecipient = null;
    }

    /**
     * Get all unique conversation partners and their latest message
     */
    private function getConversations()
    {
        $authId = Auth::id();
        
        // Get all user IDs this user has communicated with
        $conversationPartnerIds = Message::where(function($query) use ($authId) {
                $query->where('sender_id', $authId)
                      ->orWhere('recipient_id', $authId);
            })
            ->where(function($query) use ($authId) {
                // Exclude if deleted by current user
                $query->where(function($q) use ($authId) {
                    $q->where('sender_id', $authId)
                      ->where('deleted_by_sender', false);
                })
                ->orWhere(function($q) use ($authId) {
                    $q->where('recipient_id', $authId)
                      ->where('deleted_by_recipient', false);
                });
            })
            ->selectRaw('IF(sender_id = ?, recipient_id, sender_id) as partner_id', [$authId])
            ->distinct()
            ->pluck('partner_id')
            ->toArray();

        // Get the latest message for each conversation partner
        $conversations = collect();
        foreach ($conversationPartnerIds as $partnerId) {
            $latestMessage = Message::where(function($q) use ($authId, $partnerId) {
                    $q->where('sender_id', $authId)->where('recipient_id', $partnerId)
                      ->orWhere('sender_id', $partnerId)->where('recipient_id', $authId);
                })
                ->latest('created_at')
                ->first();

            if ($latestMessage) {
                // Get partner user info
                $partner = User::find($partnerId);
                $latestMessage->partner = $partner;
                $latestMessage->partner_id = $partnerId;
                
                // Count unread messages from this partner
                $unreadCount = Message::where('sender_id', $partnerId)
                    ->where('recipient_id', $authId)
                    ->where('is_read', false)
                    ->count();
                $latestMessage->unread_count = $unreadCount;
                
                $conversations->push($latestMessage);
            }
        }

        // Sort by latest message first
        $conversations = $conversations->sortByDesc('created_at');

        // Apply search filter
        if ($this->search) {
            $searchTerm = strtolower($this->search);
            $conversations = $conversations->filter(function($msg) use ($searchTerm) {
                return str_contains(strtolower($msg->partner->name ?? ''), $searchTerm);
            });
        }

        return $conversations;
    }

    /**
     * Get all messages in the selected conversation
     */
    private function getConversationMessages()
    {
        if (!$this->selectedConversationPartnerId) {
            return collect();
        }

        return Message::where(function($q) {
                $q->where('sender_id', Auth::id())->where('recipient_id', $this->selectedConversationPartnerId)
                  ->orWhere('sender_id', $this->selectedConversationPartnerId)->where('recipient_id', Auth::id());
            })
            ->latest('created_at')
            ->get()
            ->reverse(); // Show oldest first
    }

    /**
     * Get unread message count
     */
    private function getUnreadCount()
    {
        return Message::where('recipient_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Toggle broadcast form visibility (admin/ceo only)
     */
    public function toggleBroadcastForm()
    {
        if (!in_array(Auth::user()->role, ['admin', 'ceo'])) {
            return;
        }

        $this->showBroadcastForm = !$this->showBroadcastForm;
        if (!$this->showBroadcastForm) {
            $this->broadcastMessage = '';
            $this->broadcastTarget = 'all';
        }
    }

    /**
     * Send broadcast message to all or subscribed clients
     */
    public function sendBroadcast()
    {
        if (!in_array(Auth::user()->role, ['admin', 'ceo'])) {
            return;
        }

        if (!$this->broadcastMessage) {
            return;
        }

        // Get clients to send to
        $clients = User::where('role', 'client');

        // If subscribed only, filter accordingly
        // For now, we'll send to all clients
        // You can add subscription logic later
        $clientIds = $clients->pluck('id')->toArray();

        // Create broadcast messages for each client
        foreach ($clientIds as $clientId) {
            Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $clientId,
                'subject' => 'Broadcast Message',
                'body' => $this->broadcastMessage,
                'message_type' => 'broadcast',
                'delivery_status' => 'delivered',
                'delivered_at' => now(),
            ]);
        }

        $this->broadcastMessage = '';
        $this->showBroadcastForm = false;
        $this->dispatch('saved');
    }

    public function render()
    {
        $userRole = Auth::user()->role;

        return view('livewire.messaging.messaging-inbox', [
            'conversations' => $this->getConversations(),
            'selectedPartner' => $this->selectedConversationPartnerId ? User::find($this->selectedConversationPartnerId) : null,
            'conversationMessages' => $this->getConversationMessages(),
            'unreadCount' => $this->getUnreadCount(),
            'userRole' => $userRole,
        ]);
    }
}
