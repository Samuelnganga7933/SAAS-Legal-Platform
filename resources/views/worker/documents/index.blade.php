@extends('layouts.app')

@section('title', 'Documents - ' . $case->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $case->title }}</h1>
                <p class="text-slate-600 mt-2">Case #{{ $case->case_number }} - Documents</p>
            </div>
            <a href="{{ route('worker.documents.create', $case) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                + Upload Document
            </a>
        </div>

        <!-- Documents Grid -->
        @if($documents->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($documents as $document)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                @if($document->isPdf())
                                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.5 13a3 3 0 01-.369-5.98 5 5 0 1110 0"></path>
                                    </svg>
                                @elseif($document->isImage())
                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6z"></path>
                                    </svg>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-medium text-slate-900 truncate">{{ $document->filename }}</h3>
                                    <p class="text-xs text-slate-600">{{ $document->getFormattedFileSize() }}</p>
                                </div>
                            </div>
                            <div class="relative group">
                                <button class="text-slate-400 hover:text-slate-600">⋮</button>
                                <div class="hidden group-hover:block absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg z-10">
                                    <a href="{{ route('worker.documents.download', $document) }}" class="block px-4 py-2 hover:bg-slate-100 text-sm text-slate-900">Download</a>
                                    <form method="POST" action="{{ route('worker.documents.destroy', $document) }}" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this document?')" class="w-full text-left px-4 py-2 hover:bg-red-100 text-sm text-red-600">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs text-slate-600">
                            <p>👤 Uploaded by <strong>{{ $document->uploadedBy->name }}</strong></p>
                            <p>📅 {{ $document->created_at->format('M d, Y') }}</p>
                            @if($document->is_confidential)
                                <p class="text-red-600">🔒 Confidential</p>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-200">
                            <a href="{{ route('worker.documents.view', $document) }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View {{ $document->isPdf() ? 'PDF' : 'File' }} →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-slate-900 mb-2">No Documents Yet</h3>
                <p class="text-slate-600 mb-4">Upload documents to organize and track all files for this case</p>
                <a href="{{ route('worker.documents.create', $case) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Upload First Document
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
