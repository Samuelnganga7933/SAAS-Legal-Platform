@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>

        <div class="mb-6 p-4 bg-gray-50 rounded text-left">
            <div class="mb-3">
                <span class="text-gray-600 text-sm block mb-1">Payment ID:</span>
                <span class="text-sm font-mono">{{ $payment->stripe_payment_intent_id }}</span>
            </div>
            <div class="mb-3">
                <span class="text-gray-600 text-sm block mb-1">Amount:</span>
                <span class="text-lg font-bold">{{ $payment->getFormattedAmount() }}</span>
            </div>
            <div class="mb-3">
                <span class="text-gray-600 text-sm block mb-1">Paid At:</span>
                <span class="text-sm">{{ $payment->paid_at->format('M d, Y H:i:s') }}</span>
            </div>
            <div>
                <span class="text-gray-600 text-sm block mb-1">Status:</span>
                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-semibold">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
        </div>

        <p class="text-gray-600 mb-6">
            A confirmation email has been sent to <strong>{{ $payment->customer_email }}</strong>
        </p>

        <div class="space-y-2">
            <a href="{{ route('home') }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-center">
                Return to Home
            </a>
            @if(auth()->check())
                <a href="{{ route('dashboard') }}" class="block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-center">
                    Go to Dashboard
                </a>
            @endif
        </div>

        <p class="text-xs text-gray-500 mt-6">
            Thank you for your payment. We will contact you soon to confirm your consultation appointment.
        </p>
    </div>
</div>
@endsection
