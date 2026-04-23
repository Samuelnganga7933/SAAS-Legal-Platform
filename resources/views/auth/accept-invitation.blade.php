@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Complete Your Account</h1>
            <p class="text-gray-600 text-sm mt-2">You've been invited to join the team</p>
        </div>

        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-900">
                <strong>Email:</strong> {{ $user->email }}
            </p>
        </div>

        <form method="POST" action="{{ route('invitation.accept', $token) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Your name" />
                @error('name') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="At least 8 characters" />
                @error('password') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Confirm password" />
            </div>

            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Activate Account
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-900 font-medium">Log in</a>
        </p>
    </div>
</div>
@endsection
