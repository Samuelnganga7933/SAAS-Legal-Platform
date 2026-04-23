<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Consultation;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show payment form for consultation
     */
    public function showConsultationPaymentForm(Request $request)
    {
        $consultationId = $request->query('consultation_id');
        $amount = $request->query('amount', '0.00');
        $email = $request->query('email', '');
        $name = $request->query('name', '');

        return view('payments.consultation-form', [
            'consultationId' => $consultationId,
            'amount' => floatval($amount),
            'email' => $email,
            'name' => $name,
            'publishableKey' => config('services.stripe.public'),
        ]);
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'email' => 'required|email',
                'customer_name' => 'required|string',
                'description' => 'nullable|string',
                'payment_type' => 'required|in:consultation,service',
                'payable_id' => 'nullable|integer',
                'payable_type' => 'nullable|string',
                'metadata' => 'nullable|json',
            ]);

            $payment = $this->paymentService->createPaymentIntent(
                $validated['email'],
                $validated['customer_name'],
                $validated['amount'],
                $validated['description'] ?? '',
                json_decode($validated['metadata'] ?? '{}', true),
                $validated['payment_type']
            );

            if (isset($validated['payable_id']) && isset($validated['payable_type'])) {
                $payment->update([
                    'payable_id' => $validated['payable_id'],
                    'payable_type' => $validated['payable_type'],
                ]);
            }

            return response()->json([
                'success' => true,
                'clientSecret' => $payment->stripe_response['client_secret'] ?? null,
                'paymentId' => $payment->id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error creating payment intent', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to create payment. Please try again.',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error creating payment intent', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_id' => 'required|exists:payments,id',
                'payment_method_id' => 'required|string',
            ]);

            $payment = Payment::findOrFail($validated['payment_id']);

            $this->paymentService->confirmPaymentIntent(
                $payment,
                $validated['payment_method_id']
            );

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'message' => 'Payment processed successfully.',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error confirming payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Payment confirmation failed. ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error confirming payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your payment.',
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(Request $request, Payment $payment)
    {
        return response()->json([
            'id' => $payment->id,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'paid_at' => $payment->paid_at,
            'customer_email' => $payment->customer_email,
        ]);
    }

    /**
     * Refund a payment
     */
    public function refundPayment(Request $request, Payment $payment)
    {
        try {
            if ($payment->status !== Payment::STATUS_SUCCEEDED) {
                return response()->json([
                    'success' => false,
                    'error' => 'Only succeeded payments can be refunded.',
                ], 400);
            }

            $amount = $request->input('amount'); // Optional: partial refund amount

            $payment = $this->paymentService->refundPayment($payment, $amount);

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'refunded_amount' => $payment->refunded_amount,
                'message' => 'Payment refunded successfully.',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error refunding payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Refund failed: ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error refunding payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the refund.',
            ], 500);
        }
    }

    /**
     * Cancel a pending payment
     */
    public function cancelPayment(Request $request, Payment $payment)
    {
        try {
            if ($payment->status !== Payment::STATUS_PENDING) {
                return response()->json([
                    'success' => false,
                    'error' => 'Only pending payments can be canceled.',
                ], 400);
            }

            $reason = $request->input('reason', '');
            $payment = $this->paymentService->cancelPayment($payment, $reason);

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'message' => 'Payment canceled successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error canceling payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while canceling the payment.',
            ], 500);
        }
    }

    /**
     * Get payment history
     */
    public function getPaymentHistory(Request $request)
    {
        $email = $request->input('email');
        $limit = $request->input('limit', 10);

        if (!$email) {
            return response()->json([
                'success' => false,
                'error' => 'Email is required.',
            ], 400);
        }

        $payments = $this->paymentService->getPaymentHistory($email, $limit);

        return response()->json([
            'success' => true,
            'payments' => $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'type' => $payment->payment_type,
                    'amount' => $payment->getFormattedAmount(),
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at,
                    'description' => $payment->description,
                ];
            }),
        ]);
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return redirect('/')->with('error', 'Payment not found.');
        }

        return view('payments.success', [
            'payment' => $payment,
        ]);
    }

    /**
     * Show payment failure page
     */
    public function paymentFailure(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return redirect('/')->with('error', 'Payment not found.');
        }

        return view('payments.failure', [
            'payment' => $payment,
        ]);
    }

    /**
     * Admin: Show payment dashboard
     */
    public function adminDashboard()
    {
        $totalPayments = Payment::count();
        $totalRevenue = Payment::succeeded()->sum('amount');
        $successfulPayments = Payment::succeeded()->count();
        $failedPayments = Payment::failed()->count();

        $recentPayments = Payment::latest()->limit(10)->get();
        $paymentRequests = PaymentRequest::latest()->limit(10)->get();
        $refunds = Payment::where('status', Payment::STATUS_REFUNDED)->latest()->limit(10)->get();

        return view('admin.payments-dashboard', [
            'totalPayments' => $totalPayments,
            'totalRevenue' => $totalRevenue,
            'successfulPayments' => $successfulPayments,
            'failedPayments' => $failedPayments,
            'recentPayments' => $recentPayments,
            'paymentRequests' => $paymentRequests,
            'refunds' => $refunds,
        ]);
    }

    /**
     * Admin: Get payments data for API
     */
    public function getPaymentsData(Request $request)
    {
        $query = Payment::query();

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->input('payment_type'));
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to'));
        }

        // Pagination
        $limit = $request->input('limit', 50);
        $payments = $query->latest()->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $payments->items(),
            'pagination' => [
                'total' => $payments->total(),
                'per_page' => $payments->perPage(),
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
            ],
        ]);
    }

    /**
     * Admin: Refund payment
     */
    public function adminRefund(Request $request, Payment $payment)
    {
        try {
            if ($payment->status !== Payment::STATUS_SUCCEEDED) {
                return response()->json([
                    'success' => false,
                    'error' => 'Only succeeded payments can be refunded.',
                ], 400);
            }

            $amount = $request->input('amount');

            $payment = $this->paymentService->refundPayment($payment, $amount);

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'refunded_amount' => $payment->refunded_amount,
                'message' => 'Payment refunded successfully.',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe error refunding payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Refund failed: ' . $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error refunding payment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the refund.',
            ], 500);
        }
    }

    /**
     * Admin: Export payments
     */
    public function exportPayments(Request $request)
    {
        $format = $request->input('format', 'csv');
        $query = Payment::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to'));
        }

        $payments = $query->get();

        if ($format === 'csv') {
            return $this->exportCsv($payments);
        } elseif ($format === 'json') {
            return response()->json($payments);
        }

        return response()->json(['error' => 'Invalid format'], 400);
    }

    /**
     * Export payments as CSV
     */
    private function exportCsv($payments)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="payments-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Payment ID',
                'Stripe ID',
                'Customer Email',
                'Customer Name',
                'Amount',
                'Currency',
                'Status',
                'Type',
                'Paid At',
                'Refunded At',
                'Refunded Amount',
                'Created At',
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->stripe_payment_intent_id,
                    $payment->customer_email,
                    $payment->customer_name,
                    $payment->amount,
                    $payment->currency,
                    $payment->status,
                    $payment->payment_type,
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '',
                    $payment->refunded_at ? $payment->refunded_at->format('Y-m-d H:i:s') : '',
                    $payment->refunded_amount ?? '',
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
