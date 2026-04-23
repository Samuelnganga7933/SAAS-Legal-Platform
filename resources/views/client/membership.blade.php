@extends('layouts.client')

@section('title', 'Membership - Client Portal')

@section('content')
<!-- Page Header -->
<div>
    <h1 class="text-3xl font-bold text-slate-909 mb-2">Membership & Subscription</h1>
    <p class="text-gray-600">Manage your plan and billing details</p>
</div>

<!-- Current Plan Card -->
<div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-8">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">Premium Legal Advisory</h2>
            <p class="text-gray-600 mb-4">Full access to expert legal guidance and document templates</p>
            <div class="flex gap-8">
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Status</p>
                    <span class="px-3 py-1 bg-green-100 text-green-700 font-semibold text-sm rounded-full">Active</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Renewal Date</p>
                    <p class="font-semibold text-slate-900">March 15, 2026</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">Price</p>
                    <p class="font-semibold text-slate-900">$299/month</p>
                </div>
            </div>
        </div>
        <div class="text-right">
            <p class="text-5xl font-bold text-blue-600 mb-2">$299</p>
            <p class="text-gray-600">per month</p>
        </div>
    </div>
</div>

<!-- Action Buttons Grid -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Upgrade Plan Button -->
    <button class="flex flex-col items-center justify-center gap-2 p-6 border-2 border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors group">
        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6"/>
        </svg>
        <span class="font-semibold text-center">Upgrade Plan</span>
    </button>

    <!-- Billing History Button -->
    <button class="flex flex-col items-center justify-center gap-2 p-6 border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors group">
        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="font-semibold text-center">Billing History</span>
    </button>

    <!-- Payment Method Button -->
    <button class="flex flex-col items-center justify-center gap-2 p-6 border-2 border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors group">
        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h.01M11 15h.01M15 15h.01M4 6h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
        </svg>
        <span class="font-semibold text-center">Payment Method</span>
    </button>

    <!-- Cancellation Request Button -->
    <a href="{{ route('membership.cancel') }}" class="flex flex-col items-center justify-center gap-2 p-6 border-2 border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors group">
        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span class="font-semibold text-center">Request Cancellation</span>
    </a>
</div>

<!-- Plan Features -->
<div class="mt-8 bg-white rounded-lg border border-gray-200 p-6">
    <h3 class="text-lg font-bold text-slate-900 mb-4">Included Features</h3>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold text-slate-900">24/7 Email Support</p>
                <p class="text-sm text-gray-600">Quick response within 24 hours</p>
            </div>
        </div>
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold text-slate-900">Document Templates</p>
                <p class="text-sm text-gray-600">Full library of legal templates</p>
            </div>
        </div>
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold text-slate-900">Quarterly Reviews</p>
                <p class="text-sm text-gray-600">Scheduled legal reviews</p>
            </div>
        </div>
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold text-slate-900">WhatsApp Support</p>
                <p class="text-sm text-gray-600">Direct WhatsApp messaging</p>
            </div>
        </div>
    </div>
</div>
@endsection
