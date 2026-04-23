<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClientDocument;

class ClientDocumentPolicy
{
    /**
     * Determine if user can view the document
     */
    public function view(User $user, ClientDocument $document): bool
    {
        $caseCompanyId = $document->case->company_id;
        return $user->company_id === $caseCompanyId || 
               $user->id === $document->case->user_id;
    }

    /**
     * Determine if user can delete the document
     */
    public function delete(User $user, ClientDocument $document): bool
    {
        return $user->id === $document->uploaded_by_user_id || 
               $user->isAdminUser();
    }

    /**
     * Determine if user can upload documents to a case
     */
    public function upload(User $user, $case): bool
    {
        return $user->isWorker() && 
               $user->company_id === $case->company_id &&
               $user->hasActiveSubscription();
    }
}
