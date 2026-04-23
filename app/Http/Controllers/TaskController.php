<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\ClientCase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display all tasks for worker
     */
    public function index(): View
    {
        $user = auth()->user();
        $view = request()->query('view', 'list'); // 'list' or 'board'

        $tasks = $user->assignedTasks()
                     ->with('case', 'assignedTo', 'createdBy')
                     ->get()
                     ->sort(function ($a, $b) {
                         // Sort by priority (urgent > high > medium > low) then by due date
                         $priorities = ['urgent' => 0, 'high' => 1, 'medium' => 2, 'low' => 3];
                         $aP = $priorities[$a->priority] ?? 4;
                         $bP = $priorities[$b->priority] ?? 4;

                         if ($aP !== $bP) {
                             return $aP <=> $bP;
                         }

                         return $a->due_date <=> $b->due_date;
                     })
                     ->values();

        return view('worker.tasks.index', [
            'tasks' => $tasks,
            'view' => $view,
        ]);
    }

    /**
     * Show task board view
     */
    public function board(): View
    {
        $user = auth()->user();

        $columns = ['todo', 'in_progress', 'done'];
        $tasksByStatus = [];

        foreach ($columns as $status) {
            $tasksByStatus[$status] = $user->assignedTasks()
                                           ->where('status', $status === 'in_progress' ? 'in_progress' : $status)
                                           ->with('case', 'assignedTo')
                                           ->get();
        }

        return view('worker.tasks.board', [
            'tasksByStatus' => $tasksByStatus,
        ]);
    }

    /**
     * Show task detail view
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        return view('worker.tasks.show', [
            'task' => $task->load('case', 'assignedTo', 'createdBy', 'timeLogs'),
        ]);
    }

    /**
     * Show create task form
     */
    public function create(): View
    {
        $user = auth()->user();
        $cases = $user->assignedCases()->where('status', '!=', 'withdrawn')->get();

        return view('worker.tasks.create', [
            'cases' => $cases,
        ]);
    }

    /**
     * Store new task
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'case_id' => 'required|exists:cases,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['created_by_user_id'] = $user->id;
        $validated['assigned_to_user_id'] = $validated['assigned_to_user_id'] ?? $user->id;

        $task = Task::create($validated);

        return redirect()->route('worker.tasks.index')->with('success', 'Task created successfully');
    }

    /**
     * Show edit task form
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
        $user = auth()->user();
        $cases = $user->assignedCases()->where('status', '!=', 'withdrawn')->get();

        return view('worker.tasks.edit', [
            'task' => $task,
            'cases' => $cases,
        ]);
    }

    /**
     * Update task
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'case_id' => 'required|exists:cases,id',
            'assigned_to_user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,done,cancelled',
        ]);

        $task->update($validated);

        return redirect()->route('worker.tasks.index')->with('success', 'Task updated successfully');
    }

    /**
     * Update task status (for drag-and-drop board)
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,done,cancelled',
        ]);

        $task->update($validated);

        return response()->json(['success' => true, 'message' => 'Task status updated']);
    }

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('worker.tasks.index')->with('success', 'Task deleted successfully');
    }

    /**
     * Bulk update task statuses
     */
    public function bulkUpdate(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id',
            'status' => 'required|in:todo,in_progress,done,cancelled',
        ]);

        Task::whereIn('id', $validated['task_ids'])
            ->where('assigned_to_user_id', $user->id)
            ->update(['status' => $validated['status']]);

        return response()->json(['success' => true, 'message' => 'Tasks updated successfully']);
    }
}
