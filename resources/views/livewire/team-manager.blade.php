@extends('layouts.admin')

@section('content')
<div wire:loading.delay class="fixed inset-0 bg-white/50 z-50 flex items-center justify-center">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
</div>

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">Team</h1>
    <button wire:click="toggleInviteForm" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
        {{ $showInviteForm ? 'Cancel' : 'Invite member' }}
    </button>
</div>

<!-- Flash Messages -->
@if (session()->has('success'))
<div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-3">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if (session()->has('error'))
<div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-3">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Invite Member Form -->
@if ($showInviteForm)
<div class="mb-6 border-t border-b border-gray-200 bg-gray-50 px-6 py-4">
    <h3 class="text-sm font-medium text-gray-900 mb-4">Invite new member</h3>

    <form wire:submit.prevent="sendInvite" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input wire:model="inviteEmail" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="user@example.com" />
                @error('inviteEmail') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select wire:model="inviteRole" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="employee">Employee</option>
                    @if ($canInviteAdmin)
                    <option value="admin">Admin</option>
                    @endif
                </select>
                @error('inviteRole') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Send invite
                </button>
            </div>
        </div>
    </form>
</div>
@endif

<!-- Team Table -->
<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
    @if ($teamMembers->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Role</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Matters</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Joined</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($teamMembers as $member)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-sm text-gray-900 font-medium">{{ $member->name }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $member->email }}</td>

                    <!-- Role Cell -->
                    <td class="px-6 py-3 text-sm">
                        @if ($editingUserId === $member->id)
                            <select wire:model="editingRole" class="px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                                <option value="employee">Employee</option>
                                @if (auth()->user()->isCEO())
                                <option value="admin">Admin</option>
                                <option value="ceo">CEO</option>
                                @endif
                            </select>
                        @else
                            <span class="text-gray-600">{{ ucfirst($member->role) }}</span>
                        @endif
                    </td>

                    <!-- Status Badge -->
                    <td class="px-6 py-3 text-sm">
                        @if ($member->status === 'active')
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-700">Active</span>
                        @elseif ($member->status === 'invited')
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded bg-amber-100 text-amber-700">Invited</span>
                        @else
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700">Inactive</span>
                        @endif
                    </td>

                    <!-- Matters Count -->
                    <td class="px-6 py-3 text-sm text-gray-600">
                        @if ($member->getMattersCount() > 0)
                            <a href="{{ route('admin.cases') }}?assigned_to={{ $member->id }}" class="text-blue-600 hover:text-blue-900">
                                {{ $member->getMattersCount() }}
                            </a>
                        @else
                            <span class="text-gray-400">0</span>
                        @endif
                    </td>

                    <!-- Joined Date -->
                    <td class="px-6 py-3 text-sm text-gray-600">
                        {{ $member->created_at->format('M d, Y') }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-3 text-sm">
                        <div class="flex items-center gap-3">
                            @if ($editingUserId === $member->id)
                                <button wire:click="updateRole({{ $member->id }}, '{{ $editingRole }}')" class="text-blue-600 hover:text-blue-900 font-medium">
                                    Save
                                </button>
                                <button wire:click="cancelEditRole" class="text-gray-600 hover:text-gray-900">
                                    Cancel
                                </button>
                            @else
                                @if (auth()->user()->id !== $member->id)
                                    @if (auth()->user()->isCEO() || auth()->user()->hasPermission('manage_team'))
                                        <button wire:click="startEditingRole({{ $member->id }})" class="text-gray-600 hover:text-gray-900">
                                            Edit role
                                        </button>
                                        @if ($member->status === 'active')
                                        <button wire:click="deactivateUser({{ $member->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900">
                                            Deactivate
                                        </button>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $teamMembers->links() }}
    </div>
    @else
    <div class="px-6 py-8 text-center text-gray-600">
        <p>No team members yet. <button wire:click="toggleInviteForm" class="text-blue-600 hover:text-blue-900 font-medium">Invite your first member</button></p>
    </div>
    @endif
</div>
