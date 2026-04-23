@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Failed</h1>

        <div class="mb-6 p-4 bg-gray-50 rounded text-left">
            @if($payment->failure_message)
                <div class="mb-3">
                    <span class="text-gray-600 text-sm block mb-1">Error:</span>
                    <span class="text-sm text-red-600">{{ $payment->failure_message }}</span>
                </div>
            @endif
            
            <div class="mb-3">
                <span class="text-gray-600 text-sm block mb-1">Amount:</span>
                <span class="text-lg font-bold">{{ $payment->getFormattedAmount() }}</span>
            </div>
            <div>
                <span class="text-gray-600 text-sm block mb-1">Status:</span>
                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full font-semibold">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
        </div>

        <p class="text-gray-600 mb-6">
            Your payment could not be processed. Please try again with a different payment method or contact support.
        </p>

        <div class="space-y-2">
            <a href="{{ route('payments.consultation.form', ['amount' => $payment->amount, 'email' => $payment->customer_email, 'name' => $payment->customer_name]) }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-center">
                Try Again
            </a>
            <a href="{{ route('home') }}" class="block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-center">
                Return to Home
            </a>
        </div>

        <p class="text-xs text-gray-500 mt-6">
            If you need assistance, please contact our support team.
        </p>
    </div>
</div>
@endsection
