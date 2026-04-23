<?php

namespace App\Livewire;

use App\Models\CrmContact;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class CrmBoard extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $statusFilter = '';
    public $sourceFilter = '';
    public $assignedFilter = '';
    public $showAddForm = false;

    public $contactName = '';
    public $contactEmail = '';
    public $contactPhone = '';
    public $contactCompany = '';
    public $contactSource = 'direct';
    public $contactStatus = 'lead';
    public $contactNotes = '';
    public $contactAssignedTo = '';

    public function mount()
    {
        // Check authorization
        if (!auth()->user() || !in_array(auth()->user()->role, ['admin', 'ceo'])) {
            abort(403);
        }
    }

    public function toggleAddForm()
    {
        $this->showAddForm = !$this->showAddForm;
        if ($this->showAddForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset(['contactName', 'contactEmail', 'contactPhone', 'contactCompany', 'contactSource', 'contactStatus', 'contactNotes', 'contactAssignedTo']);
    }

    public function addContact()
    {
        $validated = $this->validate([
            'contactName' => 'required|string|max:255',
            'contactEmail' => 'nullable|email',
            'contactPhone' => 'nullable|string|max:20',
            'contactCompany' => 'nullable|string|max:255',
            'contactSource' => 'required|in:direct,referral,ads,website,other',
            'contactStatus' => 'required|in:lead,prospect,client,inactive',
            'contactNotes' => 'nullable|string',
            'contactAssignedTo' => 'nullable|exists:users,id',
        ]);

        CrmContact::create([
            'name' => $validated['contactName'],
            'email' => $validated['contactEmail'],
            'phone' => $validated['contactPhone'],
            'company' => $validated['contactCompany'],
            'source' => $validated['contactSource'],
            'status' => $validated['contactStatus'],
            'notes' => $validated['contactNotes'],
            'assigned_to' => $validated['contactAssignedTo'] ?: null,
            'created_by' => auth()->id(),
        ]);

        $this->showAddForm = false;
        $this->resetForm();
        session()->flash('success', 'Contact added successfully');
    }

    public function deleteContact($id)
    {
        $contact = CrmContact::find($id);
        if ($contact) {
            $contact->delete();
            session()->flash('success', 'Contact deleted');
        }
    }

    public function render()
    {
        $query = CrmContact::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->sourceFilter) {
            $query->where('source', $this->sourceFilter);
        }

        if ($this->assignedFilter) {
            $query->where('assigned_to', $this->assignedFilter);
        }

        $contacts = $query->with(['assignedTo', 'interactions'])->orderBy('created_at', 'desc')->paginate(15);
        $users = User::where('role', '!=', 'client')->get();

        return view('livewire.crm-board', [
            'contacts' => $contacts,
            'users' => $users,
        ]);
    }
}
