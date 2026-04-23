@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('crm.show', $contact) }}" class="text-sm text-blue-600 hover:text-blue-900">← Back to contact</a>
    </div>

    <h1 class="text-2xl font-semibold text-gray-900 mb-8">Edit contact</h1>

    <form action="{{ route('crm.update', $contact) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" required value="{{ old('name', $contact->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $contact->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone', $contact->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                <input type="text" name="company" value="{{ old('company', $contact->company) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                <select name="source" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="direct" {{ $contact->source === 'direct' ? 'selected' : '' }}>Direct</option>
                    <option value="referral" {{ $contact->source === 'referral' ? 'selected' : '' }}>Referral</option>
                    <option value="ads" {{ $contact->source === 'ads' ? 'selected' : '' }}>Ads</option>
                    <option value="website" {{ $contact->source === 'website' ? 'selected' : '' }}>Website</option>
                    <option value="other" {{ $contact->source === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="lead" {{ $contact->status === 'lead' ? 'selected' : '' }}>Lead</option>
                    <option value="prospect" {{ $contact->status === 'prospect' ? 'selected' : '' }}>Prospect</option>
                    <option value="client" {{ $contact->status === 'client' ? 'selected' : '' }}>Client</option>
                    <option value="inactive" {{ $contact->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('notes', $contact->notes) }}</textarea>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Save changes
            </button>
            <a href="{{ route('crm.show', $contact) }}" class="text-sm text-gray-600 hover:text-gray-900">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
