<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ClientDocument;
use App\Models\ClientCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentManager extends Component
{
    use WithFileUploads;

    public $search = '';
    public $matterFilter = 'all';
    public $typeFilter = 'all';
    public $uploaderFilter = 'all';
    
    public $showUpload = false;
    public $file;
    public $docType = '';
    public $matterId = null;
    public $notes = '';
    public $uploading = false;

    protected $rules = [
        'file' => 'required|file|max:10240|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png',
        'docType' => 'required|in:contract,evidence,court_filing,correspondence,invoice,other',
        'matterId' => 'nullable|exists:cases,id',
        'notes' => 'nullable|string|max:500',
    ];

    /**
     * Toggle upload form visibility
     */
    public function toggleUpload()
    {
        $this->showUpload = !$this->showUpload;
        if (!$this->showUpload) {
            $this->resetUploadForm();
        }
    }

    /**
     * Save uploaded document
     */
    public function uploadDocument()
    {
        $this->validate();

        $this->uploading = true;

        try {
            // Store file
            $path = $this->file->store('documents', 'local');
            
            // Create document record
            ClientDocument::create([
                'case_id' => $this->matterId,
                'uploaded_by_user_id' => Auth::id(),
                'filename' => $this->file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $this->docType,
                'file_size' => $this->file->getSize(),
                'is_confidential' => false,
            ]);

            $this->resetUploadForm();
            $this->showUpload = false;
            $this->uploading = false;
            $this->dispatch('saved');
        } catch (\Exception $e) {
            $this->uploading = false;
            session()->flash('error', 'Failed to upload document: ' . $e->getMessage());
        }
    }

    /**
     * Download a document
     */
    public function downloadDocument($documentId)
    {
        $document = ClientDocument::find($documentId);
        if (!$document) {
            return;
        }

        return Storage::download($document->file_path, $document->filename);
    }

    /**
     * Delete a document (uploader and admin/ceo only)
     */
    public function deleteDocument($documentId)
    {
        $document = ClientDocument::find($documentId);
        if (!$document) {
            return;
        }

        $authId = Auth::id();
        $userRole = Auth::user()->role;

        // Check authorization
        if ($document->uploaded_by_user_id !== $authId && !in_array($userRole, ['admin', 'ceo'])) {
            return;
        }

        // Delete file
        Storage::delete($document->file_path);
        
        // Delete record
        $document->delete();
        $this->dispatch('saved');
    }

    /**
     * Get filtered documents
     */
    private function getFilteredDocuments()
    {
        $query = ClientDocument::with('case', 'uploadedBy');

        // Search filter
        if ($this->search) {
            $query->where('filename', 'like', '%' . $this->search . '%');
        }

        // Matter filter
        if ($this->matterFilter !== 'all') {
            $query->where('case_id', $this->matterFilter);
        }

        // Type filter
        if ($this->typeFilter !== 'all') {
            $query->where('file_type', $this->typeFilter);
        }

        // Uploader filter (admin/ceo only)
        if ($this->uploaderFilter !== 'all' && in_array(Auth::user()->role, ['admin', 'ceo'])) {
            $query->where('uploaded_by_user_id', $this->uploaderFilter);
        }

        return $query->latest('created_at')->get();
    }

    /**
     * Reset upload form
     */
    private function resetUploadForm()
    {
        $this->file = null;
        $this->docType = '';
        $this->matterId = null;
        $this->notes = '';
    }

    public function render()
    {
        $documents = $this->getFilteredDocuments();
        
        // Get available matters
        $matters = ClientCase::where('status', 'open')
            ->orWhere('status', 'in-progress')
            ->get(['id', 'title']);
        
        // Get users for uploader filter (admin/ceo only)
        $uploaders = User::distinct()
            ->whereIn('id', ClientDocument::pluck('uploaded_by_user_id'))
            ->get(['id', 'name']);
        
        $userRole = Auth::user()->role;

        return view('livewire.document-manager', [
            'documents' => $documents,
            'matters' => $matters,
            'uploaders' => $uploaders,
            'userRole' => $userRole,
        ]);
    }
}
