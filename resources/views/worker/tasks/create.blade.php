@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-slate-200">
                <h1 class="text-2xl font-bold text-slate-900">Create New Task</h1>
                <p class="text-slate-600 mt-1">Add a new task to your workflow</p>
            </div>

            <form method="POST" action="{{ route('worker.tasks.store') }}" class="p-6 space-y-6">
                @csrf

                <!-- Task Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-900">Task Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" 
                           class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., Review contract documents">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Add any additional details...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Linked Case -->
                <div>
                    <label for="case_id" class="block text-sm font-medium text-slate-900">Linked Case *</label>
                    <select id="case_id" name="case_id" required
                            class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('case_id') border-red-500 @enderror">
                        <option value="">-- Select a case --</option>
                        @foreach($cases as $case)
                            <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>
                                {{ $case->title }} (Case #{{ $case->case_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('case_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-slate-900">Priority *</label>
                    <select id="priority" name="priority" required
                            class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🔵 Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-slate-900">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                           class="mt-1 block w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="border-t border-slate-200 pt-6 flex gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Create Task
                    </button>
                    <a href="{{ route('worker.tasks.board') }}" class="bg-slate-300 hover:bg-slate-400 text-slate-900 px-6 py-2 rounded-lg font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
