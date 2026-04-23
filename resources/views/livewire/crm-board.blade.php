@extends('layouts.admin')

@section('content')
<div wire:loading.delay class="fixed inset-0 bg-white/50 z-50 flex items-center justify-center">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
</div>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">CRM</h1>
    <div class="flex items-center gap-3">
        <button class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Import CSV
        </button>
        <button wire:click="toggleAddForm" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
            {{ $showAddForm ? 'Cancel' : 'Add contact' }}
        </button>
    </div>
</div>

<!-- Flash Messages -->
@if (session()->has('success'))
<div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
    {{ session('success') }}
</div>
@endif

<!-- Add Contact Form -->
@if ($showAddForm)
<div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add new contact</h3>

    <form wire:submit.prevent="addContact" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input wire:model="contactName" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                @error('contactName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="contactEmail" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                @error('contactEmail') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input wire:model="contactPhone" type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                <input wire:model="contactCompany" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                <select wire:model="contactSource" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="direct">Direct</option>
                    <option value="referral">Referral</option>
                    <option value="ads">Ads</option>
                    <option value="website">Website</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model="contactStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="lead">Lead</option>
                    <option value="prospect">Prospect</option>
                    <option value="client">Client</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned to (optional)</label>
                <select wire:model="contactAssignedTo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Unassigned</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                <textarea wire:model="contactNotes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Add contact
            </button>
            <button type="button" wire:click="toggleAddForm" class="text-sm text-gray-600 hover:text-gray-900">
                Cancel
            </button>
        </div>
    </form>
</div>
@endif

<!-- Filter Row -->
<div class="mb-6 p-4 bg-white border border-gray-200 rounded-lg flex gap-4">
    <div class="flex-1">
        <input wire:model.live="search" type="text" placeholder="Search contacts..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm" />
    </div>
    <select wire:model="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm">
        <option value="">All statuses</option>
        <option value="lead">Lead</option>
        <option value="prospect">Prospect</option>
        <option value="client">Client</option>
        <option value="inactive">Inactive</option>
    </select>
    <select wire:model="sourceFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm">
        <option value="">All sources</option>
        <option value="direct">Direct</option>
        <option value="referral">Referral</option>
        <option value="ads">Ads</option>
        <option value="website">Website</option>
        <option value="other">Other</option>
    </select>
    @if (auth()->user()->isCEO())
    <select wire:model="assignedFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm">
        <option value="">All assignees</option>
        @foreach ($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    @endif
</div>

<!-- Contacts Table -->
<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    @if ($contacts->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Company</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Source</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Assigned to</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Last interaction</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($contacts as $contact)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm">
                        <a href="{{ route('crm.show', $contact) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            {{ $contact->name }}
                        </a>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $contact->company ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $contact->email ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $contact->getSourceLabel() }}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $contact->getStatusColor() }}">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">
                        {{ $contact->assignedTo?->name ?? '-' }}
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">
                        @if ($contact->getLastInteraction())
                            {{ $contact->getLastInteraction()->interaction_date->format('d M') }} · {{ $contact->getLastInteraction()->getTypeLabel() }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('crm.show', $contact) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('crm.edit', $contact) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            <span class="text-gray-300">|</span>
                            <button wire:click="deleteContact({{ $contact->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900">Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $contacts->links() }}
    </div>
    @else
    <div class="px-6 py-12 text-center text-gray-600">
        <p>No contacts found. <button wire:click="toggleAddForm" class="text-blue-600 hover:text-blue-900 font-medium">Add your first contact</button></p>
    </div>
    @endif
</div>
