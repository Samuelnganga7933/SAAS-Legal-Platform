@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Message List Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900">Messages</h2>
                        <div class="mt-4 flex gap-2">
                            <input type="text" placeholder="Search..." class="flex-1 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-2 rounded-lg transition">🔍</button>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-200 max-h-96 overflow-y-auto">
                        @forelse($conversations as $conversation)
                            <a href="{{ route('worker.messages.show', $conversation->id) }}" class="p-4 hover:bg-slate-50 transition flex items-start gap-3 {{ request()->route('conversation') == $conversation->id ? 'bg-slate-100' : '' }}">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ substr($conversation->participant->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-slate-900 truncate">{{ $conversation->participant->name }}</p>
                                    <p class="text-sm text-slate-600 truncate">{{ $conversation->last_message?->body ?? 'No messages yet' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ $conversation->updated_at->diffForHumans() }}</p>
                                </div>
                                @if($conversation->unread_count > 0)
                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-600"></span>
                                @endif
                            </a>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-slate-600">No conversations yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Message Thread -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow h-full flex flex-col">
                    @if(isset($conversation) && $conversation)
                        <!-- Thread Header -->
                        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($conversation->participant->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-medium text-slate-900">{{ $conversation->participant->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $conversation->participant->email }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-slate-400 hover:text-slate-600 p-2">🔍</button>
                                <button class="text-slate-400 hover:text-slate-600 p-2">ℹ️</button>
                            </div>
                        </div>

                        <!-- Messages Display -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messagesContainer">
                            @forelse($conversation->messages as $message)
                                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-900' }} rounded-lg px-4 py-2">
                                        <p class="text-sm">{{ $message->body }}</p>
                                        <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-slate-500' }} mt-1">{{ $message->created_at->format('g:i A') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-slate-600">No messages yet. Start a conversation!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Message Input -->
                        <div class="px-6 py-4 border-t border-slate-200">
                            <form method="POST" action="{{ route('worker.messages.send', $conversation->id) }}" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="recipient_id" value="{{ $conversation->participant->id }}">
                                <textarea name="body" placeholder="Type your message..." rows="2" class="flex-1 border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" required></textarea>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition h-fit">
                                    Send
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 mb-2">No Conversation Selected</h3>
                                <p class="text-slate-600">Select a conversation to start messaging</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to latest message
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Optional: Polling for new messages every 3 seconds
    setInterval(function() {
        // fetch('/api/messages/new') and update
    }, 3000);
</script>
@endsection
