@extends('layouts.admin')

@section('title', 'Documents - Admin Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-909 mb-2">Documents</h1>
    <p class="text-gray-600">View and manage all client documents</p>
</div>

<!-- Documents Table -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Document Name</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Client</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Case</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Uploaded</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-900">Articles of Association</td>
                <td class="px-6 py-4 text-sm text-gray-600">James M.</td>
                <td class="px-6 py-4 text-sm text-gray-600">Company Formation</td>
                <td class="px-6 py-4 text-sm text-gray-600">Feb 5, 2026</td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">Download</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
