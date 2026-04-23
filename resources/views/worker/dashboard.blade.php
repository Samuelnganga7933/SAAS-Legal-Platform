@extends('layouts.app')

@section('title', 'Worker Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-slate-600 mt-2">Welcome back, {{ auth()->user()->name }}! Here's your legal operations overview.</p>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Active Cases -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Active Cases</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $caseStats['active'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Pending Tasks</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $taskStats['total'] }}</p>
                    </div>
                    <div class="bg-amber-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                @if($taskStats['overdue'] > 0)
                    <p class="text-xs text-red-600 mt-2">{{ $taskStats['overdue'] }} overdue</p>
                @endif
            </div>

            <!-- Unread Messages -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Unread Messages</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ $recentMessages->count() }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Hours This Week -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Hours This Week</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($weekHours, 1) }} hrs</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Tasks & Cases -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overdue Tasks Alert -->
                @if($overdueTasks->isNotEmpty())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    You have {{ $overdueTasks->count() }} overdue task{{ $overdueTasks->count() !== 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Active Cases Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-slate-900">My Cases</h2>
                        <a href="{{ route('worker.cases.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View All →</a>
                    </div>
                    <div class="divide-y divide-slate-200">
                        @forelse($cases->slice(0, 5) as $case)
                            <div class="px-6 py-4 hover:bg-slate-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-slate-900">{{ $case->title }}</h3>
                                        <p class="text-sm text-slate-600 mt-1">
                                            Case #{{ $case->case_number }} • 
                                            <span class="inline-block px-2 py-1 rounded bg-{{ $case->getStatusColor() }}-100 text-{{ $case->getStatusColor() }}-800 text-xs font-medium">
                                                {{ ucfirst(str_replace('-', ' ', $case->status)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <a href="{{ route('worker.cases.show', $case) }}" class="text-blue-600 hover:text-blue-700">→</a>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-slate-600">
                                <p>No assigned cases yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Tasks Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-slate-900">Pending Tasks</h2>
                        <a href="{{ route('worker.tasks.board') }}" class="text-sm text-blue-600 hover:text-blue-700">Kanban Board →</a>
                    </div>
                    <div class="divide-y divide-slate-200">
                        @forelse($pendingTasks->slice(0, 5) as $task)
                            <div class="px-6 py-4 hover:bg-slate-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-slate-900">{{ $task->name }}</h3>
                                        <p class="text-sm text-slate-600 mt-1">
                                            Priority: <span class="font-medium">{{ ucfirst($task->priority) }}</span>
                                            @if($task->due_date)
                                                • Due: {{ $task->due_date->format('M d') }}
                                                @if($task->isOverdue())
                                                    <span class="text-red-600">(Overdue)</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-block px-2 py-1 bg-slate-100 text-slate-800 text-xs font-medium rounded">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-slate-600">
                                <p>No pending tasks. Great job!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Actions & Messages -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('worker.tasks.create') }}" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-blue-900">New Task</span>
                        </a>
                        <a href="{{ route('worker.messages.inbox') }}" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-green-900">Check Messages</span>
                        </a>
                        <a href="{{ route('worker.time-log.current') }}" class="flex items-center p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-purple-900">Time Logger</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-slate-900">Recent Messages</h2>
                        <a href="{{ route('worker.messages.inbox') }}" class="text-sm text-blue-600 hover:text-blue-700">View All →</a>
                    </div>
                    <div class="divide-y divide-slate-200">
                        @forelse($recentMessages as $message)
                            <div class="px-6 py-3 hover:bg-slate-50 transition cursor-pointer text-sm">
                                <p class="font-medium text-slate-900">{{ $message->sender->name }}</p>
                                <p class="text-slate-600 mt-1 line-clamp-2">{{ $message->message_content }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-center text-slate-600 text-sm">
                                <p>No new messages</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
