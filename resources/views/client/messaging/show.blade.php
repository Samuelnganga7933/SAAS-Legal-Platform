@extends('layouts.client')

@section('title', 'Message - Client Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <a href="{{ route('client.messages.inbox') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold mb-2 inline-block">← Back to Messages</a>
        <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $message->subject }}</h1>
    </div>
</div>

<!-- Message Card -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <!-- Message Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-600 font-medium mb-1">From</p>
                <p class="text-lg font-semibold text-slate-900">{{ $message->sender->name }}</p>
                <p class="text-sm text-gray-600">{{ $message->sender->email }}</p>
            </div>
            <div class="text-right">
                @if($message->message_type === 'broadcast')
                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Broadcast</span>
                @else
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Direct Message</span>
                @endif
                <p class="text-xs text-gray-500 mt-2">{{ $message->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Message Body -->
    <div class="p-8">
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! nl2br(e($message->body)) !!}
        </div>
    </div>

    <!-- Message Footer -->
    <div class="bg-gray-50 border-t border-gray-200 p-6 flex items-center justify-between">
        <p class="text-xs text-gray-500">
            @if($message->is_read)
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Read on {{ $message->read_at->format('M d, Y H:i') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Delivered
                </span>
            @endif
        </p>
        <a href="{{ route('client.messages.create') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-colors">
            Reply to Admin
        </a>
    </div>
</div>
@endsection
