<?php

namespace App\Http\Controllers;

use App\Models\ClientDocument;
use App\Models\ClientCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * List documents for a case
     */
    public function index(ClientCase $case): View
    {
        $this->authorize('view', $case);

        $documents = $case->documents()->latest()->get();

        return view('worker.documents.index', [
            'case' => $case,
            'documents' => $documents,
        ]);
    }

    /**
     * Show upload form for a case
     */
    public function create(ClientCase $case): View
    {
        $this->authorize('update', $case);

        return view('worker.documents.create', [
            'case' => $case,
        ]);
    }

    /**
     * Store uploaded document
     */
    public function store(Request $request, ClientCase $case)
    {
        $this->authorize('update', $case);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,txt,jpg,jpeg,png|max:10240', // 10MB max
            'is_confidential' => 'boolean',
        ]);

        $file = $validated['file'];
        $originalName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        // Store file in storage/app/documents/{company_id}/{case_id}/
        $company = $case->company ?? auth()->user()->company;
        $path = $file->storeAs(
            "documents/{$company->id}/{$case->id}",
            $originalName,
            'local'
        );

        // Create database record
        $document = ClientDocument::create([
            'case_id' => $case->id,
            'uploaded_by_user_id' => auth()->id(),
            'filename' => $originalName,
            'file_path' => $path,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'is_confidential' => $validated['is_confidential'] ?? false,
        ]);

        // TODO: Notify client that new document was uploaded
        // TODO: Auto-create task "Review uploaded documents"

        return redirect()->route('worker.documents.index', $case)->with('success', 'Document uploaded successfully');
    }

    /**
     * View document in browser
     */
    public function view(ClientDocument $document)
    {
        $this->authorize('view', $document);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('local')->get($document->file_path);

        return response($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document->filename . '"',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Download document
     */
    public function download(ClientDocument $document)
    {
        $this->authorize('view', $document);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->download(
            $document->file_path,
            $document->filename
        );
    }

    /**
     * Delete document
     */
    public function destroy(ClientDocument $document)
    {
        $this->authorize('delete', $document);

        // Delete file from storage
        Storage::disk('local')->delete($document->file_path);

        // Soft delete from database
        $document->delete();

        // TODO: Notify client that document was removed

        return redirect()->back()->with('success', 'Document deleted successfully');
    }

    /**
     * Download all documents for a case as ZIP
     */
    public function downloadZip(ClientCase $case)
    {
        $this->authorize('view', $case);

        $documents = $case->documents()->get();

        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', 'No documents to download');
        }

        // TODO: Implement ZIP creation using a package like spatie/laravel-zipstream
        // For now, just download the first document or return an error

        return redirect()->back()->with('info', 'ZIP download not yet implemented');
    }
}

