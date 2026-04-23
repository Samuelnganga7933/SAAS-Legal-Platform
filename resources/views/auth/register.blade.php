@extends('layouts.auth')

@section('title', 'Create Account - Le Nium Legal')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Create Account</h1>
            <p class="text-gray-600">Get started with Le Nium Legal</p>
        </div>

        <!-- Registration Form -->
        <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Account Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-900 mb-3">Account Type</label>
                <div class="flex gap-4">
                    <div class="flex items-center">
                        <input type="radio" id="individual" name="account_type" value="b2c" 
                               @if(old('account_type') == 'b2c' || !old('account_type')) checked @endif
                               class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500" required>
                        <label for="individual" class="ml-2 text-sm text-gray-700 cursor-pointer">Individual</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="business" name="account_type" value="b2b"
                               @if(old('account_type') == 'b2b') checked @endif
                               class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500" required>
                        <label for="business" class="ml-2 text-sm text-gray-700 cursor-pointer">Business</label>
                    </div>
                </div>
                @error('account_type')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Full Name</label>
                <input type="text" name="name" required placeholder="John Smith" value="{{ old('name') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="your@email.com" value="{{ old('email') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
                <input type="password" name="password" required placeholder="Enter your password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required placeholder="Confirm password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('password_confirmation')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- reCAPTCHA -->
            <input type="hidden" name="recaptcha_token" id="recaptcha_token_register">
            @error('recaptcha')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Terms Checkbox -->
            <div class="flex items-start gap-3">
                <input type="checkbox" id="terms" name="terms" class="mt-1 w-4 h-4 accent-blue-600 register-terms-check" required>
                <label for="terms" class="text-sm text-gray-600">I have read, understood and do agree to the <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold">Terms of Service</a></label>
            </div>
            @error('terms')
                <p class="text-red-600 text-xs">{{ $message }}</p>
            @enderror

            <!-- Continue Button -->
            <button type="submit" id="registerSubmitBtn" disabled class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 mt-6 disabled:opacity-50 disabled:cursor-not-allowed">
                Continue
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center gap-4">
            <div class="flex-1 border-t border-gray-300"></div>
            <span class="text-gray-500 text-sm">or</span>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Google Sign Up -->
        <button type="button" onclick="window.location.href='{{ route('google.login') }}'" class="w-full flex items-center justify-center gap-3 border border-gray-300 text-slate-900 font-semibold py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continue with Google
        </button>

        <!-- Sign In Link -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Sign In</a>
        </p>
    </div>
</div>

<!-- Load reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    // reCAPTCHA setup for register form
    const registerForm = document.querySelector('form[action="{{ route('register.store') }}"]');
    if (registerForm && '{{ config('services.recaptcha.site_key') }}') {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const token = await grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                    action: 'register'
                });
                document.getElementById('recaptcha_token_register').value = token;
                registerForm.submit();
            } catch (error) {
                console.error('reCAPTCHA error:', error);
                alert('reCAPTCHA verification failed. Please try again.');
            }
        });
    }

    // Terms checkbox validation for register form
    const termsCheckbox = document.querySelector('.register-terms-check');
    const submitBtn = document.getElementById('registerSubmitBtn');

    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
    }
</script>

@endsection
