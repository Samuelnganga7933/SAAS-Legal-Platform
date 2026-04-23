@extends('layouts.app')

@section('title', 'Task Board')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Task Board</h1>
                <p class="text-slate-600 mt-2">Drag tasks between columns to update their status</p>
            </div>
            <a href="{{ route('worker.tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                + New Task
            </a>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- TODO Column -->
            <div class="bg-white rounded-lg shadow h-fit">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
                    <h2 class="font-semibold text-slate-900">📋 To Do</h2>
                    <p class="text-sm text-slate-600 mt-1">{{ $tasksByStatus['todo']->count() }} tasks</p>
                </div>
                <div class="p-4 space-y-3 min-h-96">
                    @forelse($tasksByStatus['todo'] as $task)
                        <div class="bg-slate-50 rounded-lg p-4 border-l-4 border-slate-300 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-slate-900">{{ $task->name }}</h3>
                                    @if($task->case)
                                        <p class="text-xs text-slate-600 mt-1">{{ $task->case->title }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium 
                                            @if($task->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($task->priority === 'medium') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        @if($task->due_date)
                                            <span class="text-xs text-slate-600">{{ $task->due_date->format('M d') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('worker.tasks.show', $task) }}" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 py-8">No to-do tasks</p>
                    @endforelse
                </div>
            </div>

            <!-- IN PROGRESS Column -->
            <div class="bg-white rounded-lg shadow h-fit">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-orange-50">
                    <h2 class="font-semibold text-slate-900">⏳ In Progress</h2>
                    <p class="text-sm text-slate-600 mt-1">{{ $tasksByStatus['in_progress']->count() }} tasks</p>
                </div>
                <div class="p-4 space-y-3 min-h-96">
                    @forelse($tasksByStatus['in_progress'] as $task)
                        <div class="bg-amber-50 rounded-lg p-4 border-l-4 border-amber-400 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-slate-900">{{ $task->name }}</h3>
                                    @if($task->case)
                                        <p class="text-xs text-slate-600 mt-1">{{ $task->case->title }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium 
                                            @if($task->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($task->priority === 'medium') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        @if($task->due_date)
                                            <span class="text-xs text-slate-600">{{ $task->due_date->format('M d') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('worker.tasks.show', $task) }}" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 py-8">No in-progress tasks</p>
                    @endforelse
                </div>
            </div>

            <!-- DONE Column -->
            <div class="bg-white rounded-lg shadow h-fit">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h2 class="font-semibold text-slate-900">✅ Done</h2>
                    <p class="text-sm text-slate-600 mt-1">{{ $tasksByStatus['done']->count() }} tasks</p>
                </div>
                <div class="p-4 space-y-3 min-h-96">
                    @forelse($tasksByStatus['done'] as $task)
                        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-400 cursor-move hover:shadow-md transition opacity-75" draggable="true">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-slate-900 line-through">{{ $task->name }}</h3>
                                    @if($task->case)
                                        <p class="text-xs text-slate-600 mt-1">{{ $task->case->title }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('worker.tasks.show', $task) }}" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 py-8">No completed tasks yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple drag-and-drop implementation
    let draggedElement = null;

    document.querySelectorAll('[draggable="true"]').forEach(element => {
        element.addEventListener('dragstart', (e) => {
            draggedElement = element;
            element.classList.add('opacity-50');
        });

        element.addEventListener('dragend', (e) => {
            element.classList.remove('opacity-50');
        });
    });

    document.querySelectorAll('.min-h-96').forEach(column => {
        column.addEventListener('dragover', (e) => {
            e.preventDefault();
            column.classList.add('bg-blue-50');
        });

        column.addEventListener('dragleave', (e) => {
            column.classList.remove('bg-blue-50');
        });

        column.addEventListener('drop', (e) => {
            e.preventDefault();
            column.classList.remove('bg-blue-50');
            
            if (draggedElement) {
                column.appendChild(draggedElement);
                
                // Determine new status from column
                let newStatus = 'todo';
                if (column.parentElement.querySelector('h2').textContent.includes('Progress')) {
                    newStatus = 'in_progress';
                } else if (column.parentElement.querySelector('h2').textContent.includes('Done')) {
                    newStatus = 'done';
                }

                // TODO: Send AJAX request to update task status
                // ajaxUpdateTaskStatus(draggedElement.data('task-id'), newStatus);
            }
        });
    });
</script>
@endsection
