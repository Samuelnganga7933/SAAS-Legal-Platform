@extends('layouts.admin')

@section('title', 'Messages - Admin Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Inbox</h1>
        <p class="text-gray-600">Messages from clients</p>
    </div>
    <div class="text-right">
        @if($unreadCount > 0)
            <div class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-semibold text-sm mb-2">
                {{ $unreadCount }} unread message{{ $unreadCount !== 1 ? 's' : '' }}
            </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="mb-6 flex gap-3">
    <a href="{{ route('admin.messages.create.direct') }}" class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Send Message
    </a>
    <a href="{{ route('admin.messages.create.broadcast') }}" class="px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.961 1.961 0 01-2.748 1.754l-5.175-2.995A1 1 0 003 16.561V7.04a1 1 0 011.077-.977l5.175 2.995a1.961 1.961 0 012.748 1.754zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Send Broadcast
    </a>
    <a href="{{ route('admin.messages.broadcasts') }}" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4"/>
        </svg>
        Broadcast History
    </a>
</div>

<!-- Messages List -->
<div class="space-y-4">
    @if($messages->count() > 0)
        @foreach($messages as $message)
        <a href="{{ route('admin.messages.show', $message) }}" class="block p-6 bg-white rounded-lg border border-gray-200 hover:border-blue-400 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900 {{ $message->is_read ? '' : 'font-bold' }}">
                        {{ $message->subject }}
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">From: {{ $message->sender->name }} ({{ $message->sender->email }})</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 {{ $message->is_read ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700' }} text-xs font-semibold rounded-full">{{ $message->is_read ? '✓ Read' : '🔔 Unread' }}</span>
                    <p class="text-xs text-gray-500 mt-2">{{ $message->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <p class="text-gray-700 line-clamp-2">{{ Str::limit($message->body, 150) }}</p>
        </a>
        @endforeach

        <!-- Pagination -->
        <div class="mt-8">
            {{ $messages->links() }}
        </div>
    @else
        <div class="p-12 text-center bg-white rounded-lg border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-600 font-semibold">No messages yet</p>
            <p class="text-sm text-gray-500 mt-1">Messages from clients will appear here</p>
        </div>
    @endif
</div>
@endsection
