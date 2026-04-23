<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;

class WebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Stripe webhook
     */
    public function handleStripeWebhook(Request $request)
    {
        $signature = $request->header('Stripe-Signature');
        $body = $request->getContent();

        // Verify webhook signature
        if (!$this->paymentService->verifyWebhookSignature($body, $signature)) {
            Log::warning('Invalid Stripe webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        try {
            $event = json_decode($body, true);
            $eventType = $event['type'] ?? null;

            Log::info('Stripe webhook received', ['type' => $eventType]);

            switch ($eventType) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event['data']['object']);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event['data']['object']);
                    break;

                case 'payment_intent.canceled':
                    $this->handlePaymentIntentCanceled($event['data']['object']);
                    break;

                case 'charge.refunded':
                    $this->handleChargeRefunded($event['data']['object']);
                    break;

                case 'charge.dispute.created':
                    $this->handleDisputeCreated($event['data']['object']);
                    break;

                default:
                    Log::info('Unhandled webhook event type', ['type' => $eventType]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error processing Stripe webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle payment_intent.succeeded event
     */
    protected function handlePaymentIntentSucceeded(array $paymentIntent)
    {
        $intentId = $paymentIntent['id'] ?? null;

        if (!$intentId) {
            Log::error('Missing intent ID in payment_intent.succeeded webhook');
            return;
        }

        $payment = $this->paymentService->handlePaymentIntentSucceeded($intentId);

        if ($payment) {
            Log::info('Payment succeeded webhook processed', [
                'payment_id' => $payment->id,
                'intent_id' => $intentId,
            ]);

            // You can add custom logic here, e.g., send confirmation email
            // dispatch(new SendPaymentConfirmationEmail($payment));
        }
    }

    /**
     * Handle payment_intent.payment_failed event
     */
    protected function handlePaymentIntentFailed(array $paymentIntent)
    {
        $intentId = $paymentIntent['id'] ?? null;
        $lastPaymentError = $paymentIntent['last_payment_error'] ?? null;

        if (!$intentId) {
            Log::error('Missing intent ID in payment_intent.payment_failed webhook');
            return;
        }

        $payment = $this->paymentService->handlePaymentIntentFailed($intentId, $lastPaymentError);

        if ($payment) {
            Log::warning('Payment failed webhook processed', [
                'payment_id' => $payment->id,
                'intent_id' => $intentId,
            ]);

            // You can add custom logic here, e.g., retry notification
            // dispatch(new SendPaymentRetryEmail($payment));
        }
    }

    /**
     * Handle payment_intent.canceled event
     */
    protected function handlePaymentIntentCanceled(array $paymentIntent)
    {
        $intentId = $paymentIntent['id'] ?? null;

        if (!$intentId) {
            Log::error('Missing intent ID in payment_intent.canceled webhook');
            return;
        }

        $payment = $this->paymentService->handlePaymentIntentFailed($intentId, null);

        if ($payment) {
            $payment->update(['status' => \App\Models\Payment::STATUS_CANCELED]);
            Log::info('Payment canceled webhook processed', [
                'payment_id' => $payment->id,
                'intent_id' => $intentId,
            ]);
        }
    }

    /**
     * Handle charge.refunded event
     */
    protected function handleChargeRefunded(array $charge)
    {
        $refunds = $charge['refunds']['data'] ?? [];

        foreach ($refunds as $refund) {
            $paymentIntentId = $charge['payment_intent'] ?? null;

            if ($paymentIntentId) {
                $payment = \App\Models\Payment::where('stripe_payment_intent_id', $paymentIntentId)
                    ->first();

                if ($payment) {
                    $refundAmount = $refund['amount'] / 100; // Convert from cents
                    $payment->update([
                        'status' => \App\Models\Payment::STATUS_REFUNDED,
                        'refunded_at' => now(),
                        'refunded_amount' => $refundAmount,
                    ]);

                    Log::info('Refund processed via webhook', [
                        'payment_id' => $payment->id,
                        'refund_id' => $refund['id'],
                        'amount' => $refundAmount,
                    ]);
                }
            }
        }
    }

    /**
     * Handle charge.dispute.created event
     */
    protected function handleDisputeCreated(array $dispute)
    {
        $chargeId = $dispute['charge'] ?? null;

        if ($chargeId) {
            Log::warning('Dispute created for charge', [
                'charge_id' => $chargeId,
                'dispute_id' => $dispute['id'],
                'reason' => $dispute['reason'],
            ]);

            // You can add custom logic here, e.g., notify admin
            // dispatch(new NotifyAdminOfDispute($chargeId, $dispute));
        }
    }
}
