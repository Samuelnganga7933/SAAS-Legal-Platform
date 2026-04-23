<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\ClientCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskManager extends Component
{
    public $showForm = false;
    public $title = '';
    public $matterId = null;
    public $priority = 'medium';
    public $deadline = null;
    public $assignedTo = null;
    
    public $filter = 'all'; // 'all', 'my', 'unassigned', 'overdue'
    public $sortBy = 'due_date'; // 'due_date', 'priority', 'created'
    
    public $editingId = null;

    /**
     * Toggle the add task form visibility
     */
    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    /**
     * Save a new task
     */
    public function saveTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'matterId' => 'required|exists:cases,id',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date|after:today',
        ]);

        Task::create([
            'name' => $this->title,
            'case_id' => $this->matterId,
            'priority' => $this->priority,
            'due_date' => $this->deadline,
            'assigned_to_user_id' => $this->assignedTo,
            'created_by_user_id' => Auth::id(),
            'status' => 'todo',
        ]);

        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('saved');
    }

    /**
     * Toggle task completion status
     */
    public function toggleComplete($taskId)
    {
        $task = Task::find($taskId);
        if (!$task) {
            return;
        }

        $task->update([
            'status' => $task->status === 'done' ? 'todo' : 'done',
        ]);

        $this->dispatch('saved');
    }

    /**
     * Delete a task
     */
    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->delete();
            $this->dispatch('saved');
        }
    }

    /**
     * Set the active filter
     */
    public function setFilter($filterName)
    {
        $this->filter = $filterName;
    }

    /**
     * Get filtered and sorted tasks
     */
    private function getFilteredTasks()
    {
        $authId = Auth::id();
        $query = Task::with('case', 'assignedTo', 'createdBy');

        // Apply filter
        switch ($this->filter) {
            case 'my':
                $query->where('assigned_to_user_id', $authId);
                break;
            case 'unassigned':
                $query->whereNull('assigned_to_user_id');
                break;
            case 'overdue':
                $query->where('due_date', '<', today())
                      ->where('status', '!=', 'done')
                      ->where('status', '!=', 'cancelled');
                break;
            case 'all':
            default:
                // Show all tasks
                break;
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'priority':
                $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                      ->latest('due_date');
                break;
            case 'created':
                $query->latest('created_at');
                break;
            case 'due_date':
            default:
                $query->orderBy('due_date', 'asc');
                break;
        }

        return $query->get();
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->title = '';
        $this->matterId = null;
        $this->priority = 'medium';
        $this->deadline = null;
        $this->assignedTo = null;
        $this->editingId = null;
    }

    public function render()
    {
        $tasks = $this->getFilteredTasks();
        
        // Separate completed and pending tasks
        $pendingTasks = $tasks->filter(fn($t) => $t->status !== 'done');
        $completedTasks = $tasks->filter(fn($t) => $t->status === 'done');

        // Get available matters and users
        $matters = ClientCase::where('status', 'open')
            ->orWhere('status', 'in-progress')
            ->get(['id', 'title']);
        
        $employees = User::where('role', 'employee')->get(['id', 'name']);
        $userRole = Auth::user()->role;

        return view('livewire.task-manager', [
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'matters' => $matters,
            'employees' => $employees,
            'userRole' => $userRole,
        ]);
    }
}
