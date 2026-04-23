@extends('layouts.auth')

@section('title', 'Verify Email - Le Nium Legal')

@section('content')
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 py-12" style="min-height: 100vh;">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Verify Your Email</h1>
            <p class="text-blue-100">We've sent a verification link to your email</p>
        </div>

        <div class="px-6 py-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-800 text-sm font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Instructions -->
            <div class="space-y-4 mb-6">
                <p class="text-gray-700 text-sm leading-relaxed">
                    We've sent a verification link to your registered email address. Please check your inbox and click the link to verify your account.
                </p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-blue-800 text-xs font-medium">💡 Tip:</p>
                    <p class="text-blue-700 text-sm mt-1">If you don't see the email, please check your spam or junk folder. It may take a few moments to arrive.</p>
                </div>
            </div>

            <!-- Resend Email Form -->
            <div class="border-t border-gray-200 pt-6">
                <p class="text-gray-700 text-sm font-semibold mb-4">Didn't receive the email?</p>
                <form action="{{ route('email-verification.resend') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Email Address</label>
                        <input type="email" name="email" required placeholder="your@email.com" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Resend Verification Email
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-6 py-6 bg-gray-50">
            <p class="text-center text-sm text-gray-600">
                Already verified? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Sign in here</a>
            </p>
        </div>
    </div>
</div>

@endsection
