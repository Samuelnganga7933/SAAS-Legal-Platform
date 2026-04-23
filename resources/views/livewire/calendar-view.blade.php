@extends('layouts.app')

@section('content')
<div wire:loading.delay class="fixed inset-0 bg-white/50 z-50 flex items-center justify-center">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Calendar</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="prevMonth" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors">
                ← Prev
            </button>
            <button wire:click="nextMonth" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors">
                Next →
            </button>
            <button wire:click="toggleForm" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                {{ $showAddForm ? 'Cancel' : 'Add event' }}
            </button>
        </div>
    </div>

    <!-- Add Event Form -->
    @if ($showAddForm)
    <div class="mb-8 p-6 bg-slate-50 border border-gray-200 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New Event</h3>

        <form wire:submit.prevent="saveEvent" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input wire:model="eventTitle" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Event title" />
                    @error('eventTitle') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input wire:model="eventDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                    @error('eventDate') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time (optional)</label>
                    <input wire:model="eventTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select wire:model="eventType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="deadline">Deadline</option>
                        <option value="task">Task</option>
                        <option value="call">Call</option>
                        <option value="appointment">Appointment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link to matter (optional)</label>
                    <select wire:model="eventMatterId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">Select a matter...</option>
                        @forelse ($matters as $matter)
                            <option value="{{ $matter->id }}">{{ $matter->name }}</option>
                        @empty
                            <option disabled>No matters available</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                <textarea wire:model="eventNotes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Add details..."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Save event
                </button>
                <button type="button" wire:click="toggleForm" class="text-sm text-gray-600 hover:text-gray-900">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div class="mb-6 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
        {{ session('message') }}
    </div>
    @endif

    <!-- Calendar Grid -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-8">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 gap-0">
            @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
            <div class="border-b border-gray-200 px-4 py-2 text-center text-xs font-medium text-gray-600">
                {{ $day }}
            </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-0 auto-rows-min">
            @foreach ($calendarDays as $day)
            <div class="border border-gray-200 min-h-20 p-2 {{ $day->month !== $currentMonth ? 'bg-gray-50' : '' }} {{ $day->isToday() ? 'bg-blue-50' : '' }}">
                <!-- Date Number -->
                <div class="text-xs font-medium {{ $day->isToday() ? 'text-blue-600' : 'text-gray-600' }} mb-1">
                    {{ $day->day }}
                </div>

                <!-- Events for this day -->
                <div class="space-y-1">
                    @php
                        $dayEvents = $events[$day->format('Y-m-d')] ?? [];
                    @endphp

                    @forelse ($dayEvents as $event)
                    <button
                        wire:click="selectEvent({{ $event['id'] }})"
                        class="w-full text-left text-xs px-1.5 py-0.5 rounded bg-blue-100 text-blue-900 hover:bg-blue-200 truncate transition-colors
                        {{ match($event['type']) {
                            'deadline' => 'bg-blue-100 text-blue-900 hover:bg-blue-200',
                            'task' => 'bg-amber-100 text-amber-900 hover:bg-amber-200',
                            'call', 'appointment' => 'bg-green-100 text-green-900 hover:bg-green-200',
                            default => 'bg-gray-100 text-gray-900 hover:bg-gray-200',
                        } }}"
                    >
                        {{ $event['title'] }}
                    </button>
                    @empty
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Event Detail Panel -->
    @if ($selectedEvent)
    <div class="border-t border-gray-200 pt-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $selectedEvent->title }}</h2>
                <div class="flex items-center gap-3 mt-2">
                    <span class="inline-block px-2 py-1 text-xs font-medium rounded
                    {{ match($selectedEvent->type) {
                        'deadline' => 'bg-blue-100 text-blue-900',
                        'task' => 'bg-amber-100 text-amber-900',
                        'call' => 'bg-green-100 text-green-900',
                        'appointment' => 'bg-green-100 text-green-900',
                        default => 'bg-gray-100 text-gray-900',
                    } }}">
                        {{ ucfirst($selectedEvent->type) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="editEvent({{ $selectedEvent->id }})" class="text-sm text-blue-600 hover:text-blue-900">
                    Edit
                </button>
                <button wire:click="deleteEvent({{ $selectedEvent->id }})" wire:confirm="Are you sure?" class="text-sm text-red-600 hover:text-red-900">
                    Delete
                </button>
            </div>
        </div>

        <div class="space-y-2 text-sm text-gray-600">
            <div>
                <span class="font-medium">Date:</span> {{ $selectedEvent->event_date->format('M d, Y') }}
                @if ($selectedEvent->event_time)
                at {{ \Carbon\Carbon::parse($selectedEvent->event_time)->format('h:i A') }}
                @endif
            </div>

            @if ($selectedEvent->matter)
            <div>
                <span class="font-medium">Matter:</span>
                <a href="{{ route('cases.show', $selectedEvent->matter) }}" class="text-blue-600 hover:text-blue-900">
                    {{ $selectedEvent->matter->name }}
                </a>
            </div>
            @endif

            @if ($selectedEvent->assignedUser)
            <div>
                <span class="font-medium">Assigned to:</span> {{ $selectedEvent->assignedUser->name }}
            </div>
            @endif

            @if ($selectedEvent->notes)
            <div>
                <span class="font-medium">Notes:</span> {{ $selectedEvent->notes }}
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
