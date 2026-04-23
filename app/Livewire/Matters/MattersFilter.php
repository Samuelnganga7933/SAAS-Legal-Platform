<?php

namespace App\Livewire\Matters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClientCase;
use Illuminate\Support\Facades\Auth;

class MattersFilter extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $showFilter = false;

    protected $queryString = ['search', 'statusFilter', 'typeFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function toggleFilter()
    {
        $this->showFilter = !$this->showFilter;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->typeFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = ClientCase::query();

        // Role-based filtering
        if ($user->role === 'employee') {
            $query->where('assigned_worker_id', $user->id);
        } elseif (!in_array($user->role, ['admin', 'ceo'])) {
            // Client sees only their own matters
            $query->where('user_id', $user->id);
        }

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('case_number', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Type filter
        if ($this->typeFilter) {
            $query->where('case_type', $this->typeFilter);
        }

        $matters = $query->orderBy('updated_at', 'desc')->paginate(15);
        $userRole = $user->role;

        return view('livewire.matters.matters-filter', compact('matters', 'userRole'));
    }
}
