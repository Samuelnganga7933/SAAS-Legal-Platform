@extends('layouts.auth')

@section('title', 'Account Created - Le Nium Legal')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-white flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Success Card -->
        <div class="bg-white rounded-lg shadow-xl p-8 sm:p-12 text-center">
            <!-- Success Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- Success Title -->
            <h1 class="text-3xl font-bold text-slate-900 mb-4">Account Created!</h1>

            <!-- Success Message -->
            <p class="text-gray-600 text-base leading-relaxed mb-8">
                Welcome to Le Nium Legal. Your account has been successfully created. You can now access your client portal and manage your legal services.
            </p>

            <!-- Account Details (if shown) -->
            <div class="bg-blue-50 rounded-lg p-4 mb-8 text-left">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold text-slate-900">Email:</span> {{ auth()->user()->email ?? 'your@email.com' }}
                </p>
                @if(auth()->user() && auth()->user()->account_type)
                    <p class="text-sm text-gray-700 mt-2">
                        <span class="font-semibold text-slate-900">Account Type:</span> 
                        <span class="capitalize">{{ auth()->user()->account_type === 'b2b' ? 'Business (B2B)' : 'Individual (B2C)' }}</span>
                    </p>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="bg-slate-50 rounded-lg p-4 mb-8 text-left">
                <p class="text-sm font-semibold text-slate-900 mb-3">Next Steps:</p>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold">1.</span>
                        <span>Verify your email address to unlock all features</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold">2.</span>
                        <span>Complete your profile information</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold">3.</span>
                        <span>Explore your dashboard and available services</span>
                    </li>
                </ul>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Go to Dashboard
                </a>
                <a href="{{ route('home') }}" class="block w-full border-2 border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Return to Homepage
                </a>
            </div>

            <!-- Support Note -->
            <p class="text-xs text-gray-500 mt-6">
                Need help? <a href="mailto:leniumtradinggroup@outlook.com" class="text-blue-600 hover:text-blue-700 font-semibold">Contact Support</a>
            </p>
        </div>
    </div>
</div>
@endsection
