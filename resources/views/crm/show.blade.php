@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <a href="{{ route('crm.index') }}" class="text-sm text-blue-600 hover:text-blue-900">← Back to contacts</a>
</div>

<!-- Contact Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">{{ $contact->name }}</h1>
        @if ($contact->company)
        <p class="text-sm text-gray-600 mt-1">{{ $contact->company }}</p>
        @endif
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('crm.edit', $contact) }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Edit contact
        </a>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-3 gap-8">
    <!-- Left: Interactions (60%) -->
    <div class="col-span-2">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-base font-medium text-gray-900 mb-6">Interactions</h2>

            <!-- Interactions List -->
            <div class="space-y-4 mb-8">
                @forelse ($contact->interactions as $interaction)
                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-medium text-gray-600">{{ $interaction->getTypeLabel() }}</span>
                        <span class="text-xs text-gray-500">{{ $interaction->interaction_date->format('d M Y, H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-900">{{ $interaction->summary }}</p>
                </div>
                @empty
                <p class="text-sm text-gray-600">No interactions yet.</p>
                @endforelse
            </div>

            <!-- Add Interaction Form -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Log interaction</h3>

                <form wire:submit.prevent="logInteraction" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select wire:model="interactionType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm">
                                <option value="call">Call</option>
                                <option value="email">Email</option>
                                <option value="meeting">Meeting</option>
                                <option value="note">Note</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                            <input wire:model="interactionDate" type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm" />
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                Log interaction
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                        <textarea wire:model="interactionSummary" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm"></textarea>
                    </div>

                    @if ($errors->any())
                    <div class="text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Contact Details (40%) -->
    <div class="col-span-1">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h2 class="text-base font-medium text-gray-900 mb-6">Contact details</h2>

            <div class="space-y-4 text-sm">
                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Name</p>
                    <p class="text-gray-900 mt-0.5">{{ $contact->name }}</p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Email</p>
                    @if ($contact->email)
                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-900 mt-0.5 block">{{ $contact->email }}</a>
                    @else
                    <p class="text-gray-500 mt-0.5">-</p>
                    @endif
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Phone</p>
                    @if ($contact->phone)
                    <a href="tel:{{ $contact->phone }}" class="text-blue-600 hover:text-blue-900 mt-0.5 block">{{ $contact->phone }}</a>
                    @else
                    <p class="text-gray-500 mt-0.5">-</p>
                    @endif
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Company</p>
                    <p class="text-gray-900 mt-0.5">{{ $contact->company ?? '-' }}</p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Source</p>
                    <p class="text-gray-900 mt-0.5">{{ $contact->getSourceLabel() }}</p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Status</p>
                    <span class="inline-block px-2 py-1 text-xs font-medium rounded mt-0.5 {{ $contact->getStatusColor() }}">
                        {{ ucfirst($contact->status) }}
                    </span>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Assigned to</p>
                    <p class="text-gray-900 mt-0.5">{{ $contact->assignedTo?->name ?? 'Unassigned' }}</p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Created</p>
                    <p class="text-gray-900 mt-0.5">{{ $contact->created_at->format('d M Y') }}</p>
                </div>

                @if ($contact->notes)
                <div class="border-b border-gray-200 pb-3">
                    <p class="text-xs font-medium text-gray-600">Notes</p>
                    <p class="text-gray-900 mt-0.5 whitespace-pre-wrap">{{ $contact->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
