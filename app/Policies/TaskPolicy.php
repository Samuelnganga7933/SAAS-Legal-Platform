<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    /**
     * Determine if user can view the task
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_to_user_id || 
               $user->id === $task->created_by_user_id ||
               ($task->case && $user->company_id === $task->case->company_id);
    }

    /**
     * Determine if user can update the task
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_to_user_id || 
               $user->isAdminUser();
    }

    /**
     * Determine if user can delete the task
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->created_by_user_id || 
               $user->isAdminUser();
    }

    /**
     * Determine if user can create tasks
     */
    public function create(User $user): bool
    {
        return $user->isWorker() && $user->hasActiveSubscription();
    }
}
