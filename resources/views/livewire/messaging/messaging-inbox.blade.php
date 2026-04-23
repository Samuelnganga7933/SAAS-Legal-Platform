@use('Illuminate\Support\Facades\Auth')

<!-- Save Message Toast -->
<div id="saveMessage" style="display: none; position: fixed; top: 20px; right: 20px; background-color: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 6px; font-size: 13px; z-index: 1000;">
    Broadcast sent successfully.
</div>

<script>
    Livewire.on('saved', () => {
        const msg = document.getElementById('saveMessage');
        msg.style.display = 'block';
        setTimeout(() => {
            msg.style.display = 'none';
        }, 3000);
    });
</script>

<div style="display: flex; height: calc(100vh - 80px); background-color: white;">
    
    <!-- LEFT COLUMN: Conversation List (280px) -->
    <div style="width: 280px; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow: hidden;">
        
        <!-- Search -->
        <div style="padding: 16px; border-bottom: 1px solid #e5e7eb;">
            <input type="text" wire:model.live="search" placeholder="Search conversations..." 
                style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px;">
        </div>
        
        <!-- New Conversation -->
        <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; gap: 8px;">
            <button wire:click="toggleNewConversationForm" 
                style="flex: 1; background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">
                New message
            </button>
            @if (auth()->user()->isCeo())
            <button wire:click="toggleBroadcastForm" 
                style="background-color: white; color: #1a56db; border: 1px solid #1a56db; padding: 8px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Broadcast
            </button>
            @endif
        </div>
        
        <!-- Inline New Conversation Form -->
        @if ($showNewConversationForm)
        <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
            <input type="text" wire:model.live="recipientSearch" placeholder="Search for contact..." 
                style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px; margin-bottom: 8px;">
            <div style="display: flex; gap: 8px;">
                <button wire:click="toggleNewConversationForm" 
                    style="flex: 1; background-color: #1a56db; color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; border: none; cursor: pointer;">Send</button>
                <button wire:click="toggleNewConversationForm" 
                    style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 12px;">Cancel</button>
            </div>
        </div>
        @endif
        
        <!-- Inline Broadcast Form (CEO only) -->
        @if ($showBroadcastForm && auth()->user()->isCeo())
        <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
            <textarea wire:model="broadcastMessage" rows="3" placeholder="Message to all clients..." 
                style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 12px; font-size: 13px; margin-bottom: 8px; resize: vertical;"></textarea>
            <div style="margin-bottom: 8px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; margin-bottom: 4px;">
                    <input type="radio" wire:model="broadcastTarget" value="all" style="cursor: pointer;">
                    <span>All clients</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px;">
                    <input type="radio" wire:model="broadcastTarget" value="subscribed" style="cursor: pointer;">
                    <span>Subscribed clients only</span>
                </label>
            </div>
            <div style="display: flex; gap: 8px;">
                <button wire:click="sendBroadcast()" 
                    style="flex: 1; background-color: #1a56db; color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; border: none; cursor: pointer;">Send broadcast</button>
                <button wire:click="toggleBroadcastForm()" 
                    style="background: none; border: none; color: #6b7280; cursor: pointer; font-size: 12px;">Cancel</button>
            </div>
        </div>
        @endif
        
        <!-- Conversation List -->
        <div style="flex: 1; overflow-y: auto;">
            @forelse ($conversations as $conversation)
                @php
                    $isActive = $selectedConversationPartnerId === $conversation->partner_id;
                @endphp
                <div wire:click="selectConversation({{ $conversation->partner_id }})"
                    style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; cursor: pointer; position: relative;
                        {{ $isActive ? 'background-color: #eff6ff; border-left: 2px solid #1a56db;' : 'border-left: 2px transparent;' }}
                        transition: background-color 0.15s ease;"
                    onmouseover="this.style.backgroundColor='#f9fafb'"
                    onmouseout="this.style.backgroundColor='{{ $isActive ? '#eff6ff' : 'transparent' }}'">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <span style="color: #111827; font-size: 13px; font-weight: 500;">
                            {{ $conversation->partner->name ?? 'Unknown' }}
                        </span>
                        <span style="color: #9ca3af; font-size: 11px;">{{ $conversation->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <p style="color: #6b7280; font-size: 12px; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ Str::limit($conversation->body, 50) }}
                    </p>
                    
                    @if ($conversation->unread_count > 0)
                    <div style="width: 6px; height: 6px; background-color: #1a56db; border-radius: 50%; position: absolute; right: 12px; top: 50%; transform: translateY(-50%);"></div>
                    @endif
                </div>
            @empty
                <div style="padding: 24px 16px; text-align: center;">
                    <p style="color: #6b7280; font-size: 13px;">No conversations yet.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- RIGHT COLUMN: Open Conversation -->
    <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
        @if ($selectedPartner)
            
            <!-- Conversation Header -->
            <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="color: #111827; font-size: 15px; font-weight: 500; margin: 0;">
                        {{ $selectedPartner->name }}
                    </h2>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button style="background-color: white; border: 1px solid #d1d5db; color: #374151; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">Start call</button>
                </div>
            </div>
            
            <!-- Message Thread -->
            <div style="flex: 1; overflow-y: auto; padding: 16px; background-color: #ffffff;">
                @php
                    $currentDate = null;
                @endphp
                
                @forelse ($conversationMessages as $message)
                    @php
                        $messageDate = $message->created_at->format('Y-m-d');
                        $isSent = $message->sender_id === Auth::id();
                        
                        // Format date display
                        $today = now()->format('Y-m-d');
                        $yesterday = now()->subDay()->format('Y-m-d');
                        if ($messageDate === $today) {
                            $displayDate = 'Today';
                        } elseif ($messageDate === $yesterday) {
                            $displayDate = 'Yesterday';
                        } else {
                            $displayDate = $message->created_at->format('j F');
                        }
                    @endphp
                    
                    @if ($currentDate !== $messageDate)
                        @php $currentDate = $messageDate @endphp
                        <div style="text-align: center; margin: 16px 0; color: #9ca3af; font-size: 12px;">
                            {{ $displayDate }}
                        </div>
                    @endif
                    
                    <div style="margin-bottom: 12px; display: flex; {{ $isSent ? 'justify-content: flex-end;' : 'justify-content: flex-start;' }}">
                        <div style="max-width: 65%; {{ $isSent ? 'background-color: #1a56db; color: white; border-radius: 12px 12px 0 12px;' : 'background-color: #f3f4f6; color: #111827; border-radius: 12px 12px 12px 0;' }} padding: 8px 12px;">
                            <p style="margin: 0; font-size: 13px; line-height: 1.4;">
                                {{ $message->body }}
                            </p>
                            <p style="margin: 4px 0 0 0; font-size: 11px; {{ $isSent ? 'color: rgba(255, 255, 255, 0.7);' : 'color: #9ca3af;' }}">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #9ca3af; font-size: 13px; padding-top: 24px;">
                        <p>No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Compose Area -->
            <div style="padding: 12px 16px; border-top: 1px solid #e5e7eb;">
                <textarea wire:model="newMessage" rows="2" placeholder="Type a message..." 
                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px; font-size: 13px; resize: none; margin-bottom: 8px;"></textarea>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="#" style="color: #6b7280; text-decoration: none; font-size: 13px;">Attach file</a>
                    <button wire:click="sendMessage" 
                        style="background-color: #1a56db; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;">
                        Send
                    </button>
                </div>
            </div>
            
        @else
            <!-- No conversation selected -->
            <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                <p style="color: #6b7280; font-size: 14px;">Select a conversation to view messages</p>
            </div>
        @endif
    </div>
    
</div>
