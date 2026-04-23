@extends('layouts.admin')

@section('title', 'Broadcast History - Admin Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <a href="{{ route('admin.messages.inbox') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold mb-2 inline-block">← Back to Inbox</a>
        <h1 class="text-3xl font-bold text-slate-900 mt-2">Broadcast History</h1>
        <p class="text-gray-600">View all announcements sent to clients</p>
    </div>
</div>

<!-- Action Button -->
<div class="mb-6">
    <a href="{{ route('admin.messages.create.broadcast') }}" class="px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Send New Broadcast
    </a>
</div>

<!-- Broadcasts List -->
<div class="space-y-4">
    @if($broadcasts->count() > 0)
        @foreach($broadcasts as $broadcast)
        <div class="p-6 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $broadcast->subject }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Sent to all clients</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Broadcast</span>
                    <p class="text-xs text-gray-500 mt-2">{{ $broadcast->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <p class="text-gray-700 line-clamp-2 text-sm mb-4">{{ Str::limit($broadcast->body, 200) }}</p>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 py-3 border-t border-gray-200">
                <div>
                    <p class="text-xs text-gray-600 font-medium">Sent on</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $broadcast->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-600 font-medium">Message Type</p>
                    <p class="text-sm font-semibold text-purple-600">Broadcast Announcement</p>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-8">
            {{ $broadcasts->links() }}
        </div>
    @else
        <div class="p-12 text-center bg-white rounded-lg border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.961 1.961 0 01-2.748 1.754l-5.175-2.995A1 1 0 003 16.561V7.04a1 1 0 011.077-.977l5.175 2.995a1.961 1.961 0 012.748 1.754zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-600 font-semibold">No broadcasts yet</p>
            <p class="text-sm text-gray-500 mt-1">Start by sending your first announcement to clients</p>
            <a href="{{ route('admin.messages.create.broadcast') }}" class="mt-4 inline-block px-6 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                Send First Broadcast
            </a>
        </div>
    @endif
</div>
@endsection
