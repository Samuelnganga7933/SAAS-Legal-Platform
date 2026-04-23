@extends('layouts.auth')

@section('title', 'Forgot Password - Le Nium Legal')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Reset Password</h1>
            <p class="text-gray-600">Enter your email to receive a password reset link</p>
        </div>

        <!-- Forgot Password Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf

            @if ($message = Session::get('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ $message }}
                </div>
            @endif

            <!-- Email Address -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="your@email.com" value="{{ old('email') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- reCAPTCHA -->
            <div class="flex justify-center py-2">
                <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>
            </div>

            <!-- Send Reset Link Button -->
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 mt-6">
                Send Reset Link
            </button>
        </form>

        <!-- Back to Login -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Remember your password? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Sign In</a>
        </p>
    </div>
</div>

@endsection
