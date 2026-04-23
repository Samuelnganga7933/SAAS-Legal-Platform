@extends('layouts.app')

@section('title', 'Upload Document - ' . $case->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('worker.documents.index', $case) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">← Back to Documents</a>
            <h1 class="text-3xl font-bold text-slate-900 mt-2">Upload Document</h1>
            <p class="text-slate-600 mt-2">Case: <strong>{{ $case->title }}</strong></p>
        </div>

        <!-- Upload Form Card -->
        <div class="bg-white rounded-lg shadow p-8">
            <form method="POST" action="{{ route('worker.documents.store', $case) }}" enctype="multipart/form-data">
                @csrf

                <!-- File Upload Area -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-slate-900 mb-2">Select File</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer" id="dropZone">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <p class="text-sm text-slate-600">Drag and drop your file here, or</p>
                        <label for="file" class="text-blue-600 hover:text-blue-700 font-medium cursor-pointer">click to browse</label>
                        <p class="text-xs text-slate-500 mt-2">Accepted formats: PDF, DOC, DOCX, XLSX, XLS, TXT, JPG, PNG (Max 10MB)</p>
                        <input type="file" name="file" id="file" class="hidden" required accept=".pdf,.doc,.docx,.xlsx,.xls,.txt,.jpg,.jpeg,.png">
                        <p class="text-sm text-slate-900 font-medium mt-4" id="fileName">No file selected</p>
                    </div>
                    @error('file')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confidential Checkbox -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_confidential" value="1" class="w-4 h-4 border-slate-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm text-slate-900">Mark as Confidential <span class="text-slate-600">(restricted access)</span></span>
                    </label>
                </div>

                <!-- Notes (Optional) -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-slate-900 mb-2">Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add notes about this document..."></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Upload Document
                    </button>
                    <a href="{{ route('worker.documents.index', $case) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-2 rounded-lg font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <p class="text-sm text-blue-900">
                <strong>💡 Tip:</strong> Upload important case documents like contracts, correspondence, evidence, and client communications. All uploads are timestamped and tracked by uploader.
            </p>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');
    const fileName = document.getElementById('fileName');

    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileName();
    }

    fileInput.addEventListener('change', updateFileName);

    function updateFileName() {
        if (fileInput.files.length > 0) {
            fileName.textContent = fileInput.files[0].name;
            fileName.classList.add('text-green-600');
        } else {
            fileName.textContent = 'No file selected';
            fileName.classList.remove('text-green-600');
        }
    }
</script>
@endsection
