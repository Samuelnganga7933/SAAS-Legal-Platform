<?php

namespace App\Livewire;

use App\Models\User;
use App\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class TeamManager extends Component
{
    use WithPagination;

    #[Validate('required|email|unique:users,email')]
    public $inviteEmail = '';

    #[Validate('required|in:employee,admin')]
    public $inviteRole = 'employee';

    public $showInviteForm = false;
    public $editingUserId = null;
    public $editingRole = null;

    protected $listeners = ['userUpdated' => '$refresh'];

    public function mount()
    {
        // Check authorization
        if (auth()->user()->role === 'employee') {
            abort(403);
        }
    }

    public function toggleInviteForm()
    {
        $this->showInviteForm = !$this->showInviteForm;
        if ($this->showInviteForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset(['inviteEmail', 'inviteRole']);
        $this->resetValidation();
    }

    public function sendInvite()
    {
        $this->validate();

        $user = auth()->user();

        // Check role hierarchy
        if ($this->inviteRole === 'admin' && !$user->isCEO()) {
            $this->addError('inviteRole', 'Only CEO can invite admins.');
            return;
        }

        // Create invited user
        $invitationToken = Str::random(64);
        $newUser = User::create([
            'email' => $this->inviteEmail,
            'name' => explode('@', $this->inviteEmail)[0],
            'password' => bcrypt(Str::random(32)),
            'role' => $this->inviteRole,
            'status' => 'invited',
            'invitation_token' => $invitationToken,
            'invitation_sent_at' => now(),
            'invited_by' => auth()->id(),
        ]);

        // Send invitation email
        try {
            Mail::send(new TeamInvitationMail($newUser, $invitationToken, $user));
        } catch (\Exception $e) {
            \Log::error('Failed to send team invitation email: ' . $e->getMessage());
        }

        $this->showInviteForm = false;
        $this->resetForm();
        session()->flash('success', 'Invitation sent to ' . $this->inviteEmail);
        $this->dispatch('userUpdated');
    }

    public function startEditingRole($userId)
    {
        $user = User::find($userId);
        if (!$user) return;

        // Check authorization
        if (!$this->canEditUser($user)) {
            abort(403);
        }

        $this->editingUserId = $userId;
        $this->editingRole = $user->role;
    }

    public function cancelEditRole()
    {
        $this->editingUserId = null;
        $this->editingRole = null;
    }

    public function updateRole($userId, $newRole)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        // Check authorization
        if (!$this->canEditUser($user)) {
            abort(403);
        }

        // Validate role value
        if (!in_array($newRole, ['employee', 'admin'])) {
            return;
        }

        // Check role hierarchy
        if ($newRole === 'admin' && !auth()->user()->isCEO()) {
            session()->flash('error', 'Only CEO can promote to admin.');
            return;
        }

        $user->update(['role' => $newRole]);
        $this->editingUserId = null;
        $this->editingRole = null;
        session()->flash('success', 'Role updated for ' . $user->name);
        $this->dispatch('userUpdated');
    }

    public function deactivateUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        // Check authorization
        if (!$this->canEditUser($user)) {
            abort(403);
        }

        $user->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
        ]);

        session()->flash('success', $user->name . ' has been deactivated.');
        $this->dispatch('userUpdated');
    }

    private function canEditUser(User $targetUser): bool
    {
        $currentUser = auth()->user();

        // CEO can edit anyone except themselves
        if ($currentUser->isCEO()) {
            return $currentUser->id !== $targetUser->id;
        }

        // Admin can only edit employees
        if ($currentUser->isAdmin()) {
            return $targetUser->isEmployee();
        }

        // Employees cannot edit anyone
        return false;
    }

    public function render()
    {
        $teamMembers = User::where('role', '!=', 'client')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.team-manager', [
            'teamMembers' => $teamMembers,
            'canInviteAdmin' => auth()->user()->isCEO(),
        ]);
    }
}
