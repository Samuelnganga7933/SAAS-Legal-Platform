@extends('layouts.admin')

@section('title', 'Send Message - Admin Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <a href="{{ route('admin.messages.inbox') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold mb-2 inline-block">← Back to Inbox</a>
    <h1 class="text-3xl font-bold text-slate-900 mt-2">Send Direct Message</h1>
    <p class="text-gray-600 mt-2">Send a message to a specific client</p>
</div>

<!-- Form Card -->
<div class="bg-white rounded-lg border border-gray-200 p-8 max-w-2xl">
    <form action="{{ route('admin.messages.send.direct') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Client Selection -->
        <div>
            <label for="client_id" class="block text-sm font-semibold text-slate-900 mb-2">Select Client</label>
            <select 
                id="client_id" 
                name="client_id" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('client_id') border-red-500 @enderror"
                required
            >
                <option value="">-- Choose a client --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }} ({{ $client->email }})
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Subject -->
        <div>
            <label for="subject" class="block text-sm font-semibold text-slate-900 mb-2">Subject</label>
            <input 
                type="text" 
                id="subject" 
                name="subject" 
                placeholder="Message subject"
                value="{{ old('subject') }}"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 @error('subject') border-red-500 @enderror"
                required
            >
            @error('subject')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message Body -->
        <div>
            <label for="body" class="block text-sm font-semibold text-slate-900 mb-2">Message</label>
            <textarea 
                id="body" 
                name="body" 
                placeholder="Type your message here..."
                rows="8"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 resize-none @error('body') border-red-500 @enderror"
                required
            >{{ old('body') }}</textarea>
            @error('body')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-2">Maximum 5000 characters</p>
        </div>

        <!-- Send Email Notification -->
        <div class="flex items-center gap-3">
            <input 
                type="checkbox" 
                id="send_email" 
                name="send_email" 
                value="1"
                class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-600"
                {{ old('send_email') ? 'checked' : '' }}
            >
            <label for="send_email" class="text-sm font-semibold text-slate-900">
                Also send to client's email
            </label>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 pt-4 border-t border-gray-200">
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Message
            </button>
            <a 
                href="{{ route('admin.messages.inbox') }}" 
                class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
