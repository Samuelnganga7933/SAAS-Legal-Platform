@extends('layouts.client')

@section('title', 'Messages - Client Portal')

@section('content')
<livewire:messaging.messaging-inbox />
@endsection


<!-- Page Header -->
<div style="margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h1 style="color: var(--color-dark-text);" class="text-3xl font-bold mb-2">Messages</h1>
        <p style="color: var(--color-secondary-text);" class="text-sm">Conversations with your legal advisors</p>
    </div>
    <a href="{{ route('client.messages.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Message
    </a>
</div>

<!-- Messages Container - Two Panel Layout -->
@if($messages->count() > 0)
<div style="display: grid; grid-template-columns: 340px 1fr; gap: 24px; height: 600px; background-color: var(--color-card-surface); border-radius: 10px; border: 1px solid var(--color-border); overflow: hidden;">
    
    <!-- Left Panel: Inbox List -->
    <div style="border-right: 1px solid var(--color-border); overflow-y: auto;">
        @foreach($messages as $index => $message)
            <a href="{{ route('client.messages.show', $message) }}" class="message-item {{ !$message->is_read ? 'unread' : '' }}" onclick="selectMessage(event, {{ $index }})">
                <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 8px;">
                    <div style="background: linear-gradient(135deg, var(--color-soft-blue), var(--color-primary-blue)); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; flex-shrink: 0;">
                        {{ substr($message->sender->name ?? 'U', 0, 1) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="color: var(--color-dark-text); font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $message->sender->name }}</p>
                        <p style="color: var(--color-secondary-text); font-size: 12px; margin: 4px 0 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $message->subject }}</p>
                    </div>
                </div>
                <p style="color: var(--color-secondary-text); font-size: 12px; margin: 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($message->body, 100) }}</p>
                <p style="color: var(--color-secondary-text); font-size: 11px; margin-top: 8px; margin-bottom: 0;">{{ $message->created_at->format('M d, H:i') }}</p>
            </a>
        @endforeach
    </div>

    <!-- Right Panel: Conversation Preview -->
    <div style="padding: 24px; display: flex; flex-direction: column; background-color: #FAFBFC;">
        @if($messages->count() > 0)
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--color-border);">
                <h2 style="color: var(--color-dark-text); font-size: 18px; font-weight: 600; margin: 0 0 8px 0;">{{ $messages->first()->subject }}</h2>
                <p style="color: var(--color-secondary-text); font-size: 14px; margin: 0;">From: <strong>{{ $messages->first()->sender->name }}</strong></p>
            </div>
            
            <div style="flex: 1; overflow-y: auto; margin-bottom: 24px; padding-right: 8px;">
                <div style="background-color: #F1F5F9; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="color: var(--color-secondary-text); font-size: 12px; margin: 0 0 8px 0; font-weight: 600;">{{ $messages->first()->sender->name }}</p>
                    <p style="color: var(--color-dark-text); font-size: 14px; margin: 0; line-height: 1.6;">{{ $messages->first()->body }}</p>
                </div>
            </div>
            
            <div style="padding-top: 16px; border-top: 1px solid var(--color-border);">
                <a href="{{ route('client.messages.show', $messages->first()) }}" style="background-color: var(--color-primary-blue); color: white; padding: 10px 16px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; display: inline-block; transition: all 0.15s ease; font-size: 14px; text-decoration: none;">
                    View Full Conversation
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($messages->hasPages())
<div style="margin-top: 24px; padding: 16px; background-color: var(--color-card-surface); border: 1px solid var(--color-border); border-radius: 10px;">
    {{ $messages->links() }}
</div>
@endif
@else
<!-- Empty State -->
<div class="empty-state" style="background-color: var(--color-card-surface); border: 1px solid var(--color-border); border-radius: 10px;">
    <svg style="width: 80px; height: 80px; color: var(--color-border); margin-bottom: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
    <h2 style="color: var(--color-dark-text); font-size: 18px; font-weight: 600; margin-bottom: 8px;">No Messages Yet</h2>
    <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 24px;">Start a conversation with your legal advisor</p>
    <a href="{{ route('client.messages.create') }}" class="btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Send New Message
    </a>
</div>
@endif

<!-- Info Cards -->
<div style="margin-top: 32px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
    <div style="background-color: var(--color-card-surface); border: 1px solid var(--color-border); border-radius: 10px; padding: 20px;">
        <p style="color: var(--color-dark-text); font-weight: 600; font-size: 14px; margin: 0 0 8px 0;">Inbox Status</p>
        <p style="color: var(--color-secondary-text); font-size: 13px; margin: 0;">{{ $unreadCount ?? 0 }} unread message{{ ($unreadCount ?? 0) !== 1 ? 's' : '' }}</p>
    </div>
    <div style="background-color: var(--color-card-surface); border: 1px solid var(--color-border); border-radius: 10px; padding: 20px;">
        <p style="color: var(--color-dark-text); font-weight: 600; font-size: 14px; margin: 0 0 8px 0;">24/7 Support</p>
        <p style="color: var(--color-secondary-text); font-size: 13px; margin: 0;">Always available for your needs</p>
    </div>
</div>

<script>
function selectMessage(event, index) {
    // This is a placeholder - actual behavior would require more JS/AJAX
    // The link will navigate to the message detail page
    // event.preventDefault();
    // Load and display message in right panel via AJAX
}
</script>
@endsection
