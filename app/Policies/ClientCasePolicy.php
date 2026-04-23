<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClientCase;

class ClientCasePolicy
{
    /**
     * Determine if user can view the case
     */
    public function view(User $user, ClientCase $case): bool
    {
        // Worker can view if assigned or in same company
        if ($user->isWorker()) {
            return $user->id === $case->assigned_worker_id || 
                   $user->company_id === $case->company_id;
        }

        // Client can view if it's their case
        return $user->id === $case->user_id;
    }

    /**
     * Determine if user can update the case
     */
    public function update(User $user, ClientCase $case): bool
    {
        return $user->isWorker() && 
               ($user->id === $case->assigned_worker_id || $user->isAdminUser());
    }

    /**
     * Determine if user can delete the case
     */
    public function delete(User $user, ClientCase $case): bool
    {
        return $user->isAdminUser();
    }

    /**
     * Determine if user can reassign the case
     */
    public function reassign(User $user, ClientCase $case): bool
    {
        return $user->isWorker() && 
               $user->company_id === $case->company_id &&
               ($user->id === $case->assigned_worker_id || $user->isAdminUser());
    }

    /**
     * Determine if user can withdraw the case
     */
    public function withdraw(User $user, ClientCase $case): bool
    {
        return $user->isWorker() && $user->id === $case->assigned_worker_id;
    }
}
