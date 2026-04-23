@extends('layouts.client')

@section('title', 'Cancel Membership - Client Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <a href="{{ route('membership') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-semibold text-sm mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Membership
    </a>
    <h1 class="text-3xl font-bold text-slate-900 mb-2">Submit Cancellation Request</h1>
    <p class="text-gray-600">Cancellation requests are reviewed by our administrative team</p>
</div>

<!-- Cancellation Form Card -->
<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <form action="{{ route('membership.cancel.submit') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Warning Message -->
        <div class="bg-amber-50 border border-amber-300 rounded-lg p-4 flex gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-amber-800">Cancelling your subscription will remove access to premium features and ongoing support.</p>
        </div>

        <!-- Reason for Cancellation -->
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-3">Reason for Cancellation *</label>
            <select name="reason" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                <option value="">-- Select a reason --</option>
                <option value="service_no_longer_needed">Service no longer required</option>
                <option value="pricing_concerns">Pricing concerns</option>
                <option value="switching_providers">Switching providers</option>
                <option value="insufficient_features">Insufficient features</option>
                <option value="poor_support">Poor customer support</option>
                <option value="other">Other</option>
            </select>
            @error('reason')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Additional Notes -->
        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-3">Additional Notes</label>
            <textarea name="notes" rows="5" placeholder="Provide additional context (optional)..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none"></textarea>
            <p class="text-xs text-gray-500 mt-1">Help us improve by sharing your feedback</p>
        </div>

        <!-- Terms Checkbox -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="confirm" required class="w-4 h-4 mt-1 accent-red-600">
                <span class="text-sm text-gray-700">I understand that canceling my membership will end all services and support. I request to cancel my subscription effective immediately.</span>
            </label>
            @error('confirm')
                <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 pt-4">
            <a href="{{ route('membership') }}" class="flex-1 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition-colors text-center">
                Cancel
            </a>
            <button type="submit" class="flex-1 bg-red-600 text-white font-semibold py-2.5 rounded-lg hover:bg-red-700 transition-colors">
                Submit Cancellation Request
            </button>
        </div>
    </form>
</div>

<!-- Help Section -->
<div class="mt-8 bg-blue-50 rounded-lg border border-blue-200 p-6">
    <h3 class="font-bold text-slate-900 mb-3">Need Help?</h3>
    <p class="text-gray-700 text-sm mb-3">Before canceling, please consider our other plans or contact us for personalized assistance.</p>
    <div class="flex gap-4">
        <a href="mailto:leniumtradinggroup@outlook.com" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">Contact Support</a>
        <a href="https://wa.me/254104921009" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">Chat on WhatsApp</a>
    </div>
</div>
@endsection
