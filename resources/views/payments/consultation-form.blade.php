@extends('layouts.app')

@section('title', 'Consultation Payment')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold mb-6">Complete Your Payment</h1>

        <div class="mb-6 p-4 bg-gray-50 rounded">
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Consultation Fee:</span>
                <span class="font-semibold">${{ number_format($amount, 2) }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Email:</span>
                <span class="text-sm">{{ $email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Name:</span>
                <span class="text-sm">{{ $name }}</span>
            </div>
        </div>

        <form id="payment-form">
            @csrf
            <input type="hidden" id="amount" value="{{ $amount }}">
            <input type="hidden" id="email" value="{{ $email }}">
            <input type="hidden" id="customer-name" value="{{ $name }}">
            <input type="hidden" id="consultation-id" value="{{ $consultationId ?? '' }}">

            <div id="card-element" class="border border-gray-300 rounded px-4 py-3 mb-4"></div>
            <div id="card-errors" role="alert" class="text-red-600 text-sm mb-4"></div>

            <button type="submit" id="submit-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Pay ${{ number_format($amount, 2) }}
            </button>
        </form>

        <p class="text-xs text-gray-500 text-center mt-4">
            Your payment is secured with Stripe
        </p>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ $publishableKey }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const cardErrors = document.getElementById('card-errors');
    const form = document.getElementById('payment-form');

    cardElement.on('change', function(event) {
        if (event.error) {
            cardErrors.textContent = event.error.message;
        } else {
            cardErrors.textContent = '';
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';

        try {
            // Create payment intent
            const response = await fetch('/payments/create-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    amount: parseFloat(document.getElementById('amount').value),
                    email: document.getElementById('email').value,
                    customer_name: document.getElementById('customer-name').value,
                    description: 'Consultation Payment',
                    payment_type: 'consultation',
                    payable_id: document.getElementById('consultation-id').value || null,
                    payable_type: 'App\\Models\\Consultation'
                })
            });

            const data = await response.json();

            if (!data.success) {
                cardErrors.textContent = data.error || 'Failed to create payment intent';
                submitButton.disabled = false;
                submitButton.textContent = 'Pay $' + document.getElementById('amount').value;
                return;
            }

            // Confirm payment with Stripe
            const { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        email: document.getElementById('email').value,
                        name: document.getElementById('customer-name').value
                    }
                }
            });

            if (error) {
                cardErrors.textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Pay $' + document.getElementById('amount').value;
            } else if (paymentIntent.status === 'succeeded') {
                window.location.href = '/payments/success?payment_id=' + data.paymentId;
            } else if (paymentIntent.status === 'requires_action') {
                cardErrors.textContent = 'Additional authentication required. Please follow the prompts.';
                submitButton.disabled = false;
                submitButton.textContent = 'Pay $' + document.getElementById('amount').value;
            }
        } catch (error) {
            cardErrors.textContent = 'An error occurred: ' + error.message;
            submitButton.disabled = false;
            submitButton.textContent = 'Pay $' + document.getElementById('amount').value;
        }
    });
</script>
@endsection
