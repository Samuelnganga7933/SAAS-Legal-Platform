<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientBillingController extends Controller
{
    /**
     * Show client billing dashboard
     */
    public function index(): View
    {
        $user = auth()->user();

        $invoices = Payment::where('user_id', $user->id)
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('billing.client-index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show upgrade plan page
     */
    public function upgradePlan(): View
    {
        $plans = config('plans');

        return view('billing.upgrade-plan', [
            'plans' => $plans,
        ]);
    }

    /**
     * Process plan upgrade
     */
    public function processPlanUpgrade(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:basic,business,enterprise',
        ]);

        $user = auth()->user();
        $plans = config('plans');
        $selectedPlan = $plans[$validated['plan']];

        // TODO: Integrate with Stripe to process upgrade
        // For now, just update the user subscription
        $user->update([
            'subscription_plan' => $validated['plan'],
            'monthly_rate' => $selectedPlan['price'],
            'subscription_start_date' => now(),
            'next_billing_date' => now()->addMonth(),
            'is_subscribed' => true,
        ]);

        return redirect()->route('client.billing')->with('success', 'Plan upgraded successfully');
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Request $request)
    {
        $user = auth()->user();

        // TODO: Integrate with Stripe to cancel subscription
        $user->update([
            'is_subscribed' => false,
            'subscription_plan' => null,
        ]);

        return redirect()->route('client.billing')->with('success', 'Subscription cancelled');
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice(Payment $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        // TODO: Generate and download PDF invoice using a service like TCPDF or Dompdf
        // For now, return a placeholder
        return response()->json(['message' => 'PDF generation in progress']);
    }

    /**
     * Show payment form for unpaid invoice
     */
    public function payInvoice(Payment $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        return view('billing.pay-invoice', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show add payment method page
     */
    public function addPaymentMethod(): View
    {
        return view('billing.add-payment-method');
    }

    /**
     * Show update payment method page
     */
    public function updatePaymentMethod(): View
    {
        $user = auth()->user();

        return view('billing.update-payment-method', [
            'user' => $user,
        ]);
    }

    /**
     * Process payment method update
     */
    public function processPaymentMethodUpdate(Request $request)
    {
        $user = auth()->user();

        // TODO: Integrate with Stripe to update payment method
        // This would typically involve creating a Stripe payment element
        
        return redirect()->route('client.billing')->with('success', 'Payment method updated');
    }
}
