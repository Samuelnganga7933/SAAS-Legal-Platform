<?php

namespace App\Livewire\Matters;

use Livewire\Component;
use App\Models\ClientCase;
use App\Models\CaseNote;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MatterDetail extends Component
{
    public ClientCase $matter;
    public $newNote = '';
    public $editingDescription = false;
    public $descriptionText = '';
    
    // Action form states
    public $showAssignForm = false;
    public $selectedAssigneeId = null;
    public $showStatusForm = false;
    public $selectedStatus = null;
    public $showDeadlineForm = false;
    public $selectedDeadline = null;

    public function mount(ClientCase $matter)
    {
        $this->matter = $matter;
        $this->descriptionText = $matter->description;
        $this->selectedAssigneeId = $matter->assigned_worker_id;
        $this->selectedStatus = $matter->status;
    }

    public function addNote()
    {
        if (!$this->newNote) {
            return;
        }

        // Save internal note for this matter
        CaseNote::create([
            'case_id' => $this->matter->id,
            'user_id' => Auth::id(),
            'note' => $this->newNote,
        ]);

        $this->newNote = '';
        $this->dispatch('noteSaved');

        // Reload matter to get updated notes
        $this->matter = ClientCase::find($this->matter->id);
    }

    public function updateDescription()
    {
        if (!in_array(Auth::user()->role, ['employee', 'admin', 'ceo'])) {
            return;
        }

        $this->matter->update(['description' => $this->descriptionText]);
        $this->editingDescription = false;
        $this->dispatch('descriptionUpdated');
    }

    /**
     * Toggle assign form visibility
     */
    public function toggleAssignForm()
    {
        $this->showAssignForm = !$this->showAssignForm;
        if ($this->showAssignForm) {
            $this->selectedAssigneeId = $this->matter->assigned_worker_id;
        }
    }

    /**
     * Assign matter to a team member
     */
    public function assignMatter()
    {
        if (!$this->selectedAssigneeId) {
            return;
        }

        $this->matter->update(['assigned_worker_id' => $this->selectedAssigneeId]);
        $this->showAssignForm = false;
        $this->dispatch('saved');
        $this->matter = ClientCase::find($this->matter->id);
    }

    /**
     * Toggle status form visibility
     */
    public function toggleStatusForm()
    {
        $this->showStatusForm = !$this->showStatusForm;
        if ($this->showStatusForm) {
            $this->selectedStatus = $this->matter->status;
        }
    }

    /**
     * Update case status
     */
    public function updateStatus()
    {
        if (!$this->selectedStatus) {
            return;
        }

        $this->matter->update(['status' => $this->selectedStatus]);
        $this->showStatusForm = false;
        $this->dispatch('saved');
        $this->matter = ClientCase::find($this->matter->id);
    }

    /**
     * Toggle deadline form visibility
     */
    public function toggleDeadlineForm()
    {
        $this->showDeadlineForm = !$this->showDeadlineForm;
    }

    /**
     * Set case deadline
     */
    public function setDeadline()
    {
        if (!$this->selectedDeadline) {
            return;
        }

        // This assumes you have a deadline field or similar in the cases table
        // If not, you may need to adjust this based on your schema
        $this->matter->update(['filed_date' => $this->selectedDeadline]); // Adjust field as needed
        $this->showDeadlineForm = false;
        $this->dispatch('saved');
        $this->matter = ClientCase::find($this->matter->id);
    }

    /**
     * Close a matter
     */
    public function closeMatter()
    {
        $this->matter->update([
            'status' => 'closed',
            'closed_date' => now(),
        ]);
        $this->dispatch('saved');
        $this->matter = ClientCase::find($this->matter->id);
    }

    public function render()
    {
        $userRole = Auth::user()->role;
        $notes = $this->matter->caseNotes()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get list of employees for assignment
        $employees = User::where('role', 'employee')->get(['id', 'name']);
        
        // Status options
        $statuses = ['open', 'in-progress', 'closed', 'withdrawn'];

        return view('livewire.matters.matter-detail', compact('userRole', 'notes', 'employees', 'statuses'));
    }
}
