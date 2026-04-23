<?php

namespace App\Services;

use App\Models\Payment;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for a consultation
     */
    public function createPaymentIntent(
        string $email,
        string $customerName,
        float $amount,
        string $description = '',
        array $metadata = [],
        string $paymentType = Payment::TYPE_CONSULTATION
    ): Payment {
        try {
            // Create Stripe Payment Intent
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => (int) round($amount * 100), // Convert to cents
                'currency' => config('services.stripe.currency', 'usd'),
                'description' => $description,
                'metadata' => array_merge($metadata, [
                    'email' => $email,
                    'customer_name' => $customerName,
                    'payment_type' => $paymentType,
                ]),
                'receipt_email' => $email,
            ]);

            // Create Payment record
            $payment = Payment::create([
                'stripe_payment_intent_id' => $paymentIntent->id,
                'payment_type' => $paymentType,
                'customer_email' => $email,
                'customer_name' => $customerName,
                'amount' => $amount,
                'currency' => strtoupper(config('services.stripe.currency', 'usd')),
                'status' => Payment::STATUS_PENDING,
                'description' => $description,
                'metadata' => $metadata,
                'stripe_response' => $paymentIntent,
                'ip_address' => request()->ip(),
            ]);

            Log::info('Payment intent created', [
                'payment_id' => $payment->id,
                'stripe_intent_id' => $paymentIntent->id,
                'amount' => $amount,
            ]);

            return $payment;
        } catch (ApiErrorException $e) {
            Log::error('Failed to create payment intent', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            throw $e;
        }
    }

    /**
     * Confirm a payment intent
     */
    public function confirmPaymentIntent(
        Payment $payment,
        string $paymentMethodId
    ): Payment {
        try {
            $paymentIntent = $this->stripe->paymentIntents->confirm(
                $payment->stripe_payment_intent_id,
                ['payment_method' => $paymentMethodId]
            );

            $this->updatePaymentFromIntent($payment, $paymentIntent);

            if ($paymentIntent->status === 'succeeded') {
                $payment->markAsSucceeded($paymentIntent);
            } elseif ($paymentIntent->status === 'requires_action') {
                $payment->update(['status' => Payment::STATUS_PROCESSING]);
            }

            return $payment;
        } catch (ApiErrorException $e) {
            $payment->markAsFailed($e->getMessage());
            Log::error('Failed to confirm payment intent', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Retrieve a payment intent from Stripe
     */
    public function getPaymentIntent(string $intentId): \Stripe\PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve($intentId);
    }

    /**
     * Update payment status from Stripe intent
     */
    public function updatePaymentFromIntent(Payment $payment, \Stripe\PaymentIntent $intent): Payment
    {
        $statusMap = [
            'succeeded' => Payment::STATUS_SUCCEEDED,
            'processing' => Payment::STATUS_PROCESSING,
            'requires_payment_method' => Payment::STATUS_PENDING,
            'requires_confirmation' => Payment::STATUS_PENDING,
            'requires_action' => Payment::STATUS_PROCESSING,
            'canceled' => Payment::STATUS_CANCELED,
        ];

        $status = $statusMap[$intent->status] ?? Payment::STATUS_PENDING;

        $payment->update([
            'status' => $status,
            'payment_method' => $intent->payment_method,
            'stripe_response' => $intent,
        ]);

        if ($intent->status === 'succeeded') {
            $payment->update(['paid_at' => now()]);
        }

        return $payment;
    }

    /**
     * Refund a payment
     */
    public function refundPayment(Payment $payment, ?float $refundAmount = null): Payment
    {
        try {
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $payment->stripe_payment_intent_id,
                'amount' => $refundAmount ? (int) round($refundAmount * 100) : null,
                'metadata' => [
                    'payment_id' => $payment->id,
                ],
            ]);

            $payment->markAsRefunded($refundAmount);

            Log::info('Payment refunded', [
                'payment_id' => $payment->id,
                'refund_id' => $refund->id,
                'amount' => $refundAmount,
            ]);

            return $payment;
        } catch (ApiErrorException $e) {
            Log::error('Failed to refund payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Cancel a pending payment
     */
    public function cancelPayment(Payment $payment, string $reason = ''): Payment
    {
        try {
            if ($payment->stripe_payment_intent_id) {
                $this->stripe->paymentIntents->cancel(
                    $payment->stripe_payment_intent_id,
                    ['cancellation_reason' => $reason ?: 'requested_by_customer']
                );
            }

            $payment->update(['status' => Payment::STATUS_CANCELED]);

            Log::info('Payment canceled', [
                'payment_id' => $payment->id,
                'reason' => $reason,
            ]);

            return $payment;
        } catch (ApiErrorException $e) {
            Log::error('Failed to cancel payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $body, string $signature): bool
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $body,
                $signature,
                config('services.stripe.webhook_secret')
            );
            return true;
        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Handle payment intent succeeded webhook
     */
    public function handlePaymentIntentSucceeded(string $intentId): ?Payment
    {
        $payment = Payment::where('stripe_payment_intent_id', $intentId)->first();

        if ($payment) {
            $intent = $this->getPaymentIntent($intentId);
            $payment->markAsSucceeded($intent);
            
            Log::info('Payment confirmed via webhook', [
                'payment_id' => $payment->id,
                'intent_id' => $intentId,
            ]);
        }

        return $payment;
    }

    /**
     * Handle payment intent failed webhook
     */
    public function handlePaymentIntentFailed(string $intentId, $lastPaymentError): ?Payment
    {
        $payment = Payment::where('stripe_payment_intent_id', $intentId)->first();

        if ($payment) {
            $errorMessage = $lastPaymentError->message ?? 'Payment failed';
            $payment->markAsFailed($errorMessage);
            
            Log::warning('Payment failed via webhook', [
                'payment_id' => $payment->id,
                'intent_id' => $intentId,
                'error' => $errorMessage,
            ]);
        }

        return $payment;
    }

    /**
     * Retrieve customer's payment history
     */
    public function getPaymentHistory(string $email, int $limit = 10)
    {
        return Payment::where('customer_email', $email)
            ->whereIn('status', [Payment::STATUS_SUCCEEDED, Payment::STATUS_REFUNDED])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats(array $filters = [])
    {
        $query = Payment::query();

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['payment_type'])) {
            $query->where('payment_type', $filters['payment_type']);
        }

        return [
            'total_succeeded' => (clone $query)->succeeded()->sum('amount'),
            'total_failed' => (clone $query)->failed()->sum('amount'),
            'total_refunded' => (clone $query)->where('status', Payment::STATUS_REFUNDED)->sum('refunded_amount'),
            'count_succeeded' => (clone $query)->succeeded()->count(),
            'count_failed' => (clone $query)->failed()->count(),
            'count_pending' => (clone $query)->pending()->count(),
        ];
    }
}
