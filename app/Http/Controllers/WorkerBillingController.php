<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerBillingController extends Controller
{
    /**
     * Show billing dashboard
     */
    public function dashboard(): View
    {
        $company = auth()->user()->company;

        // Get invoice history (mock data for now, integrate with Stripe later)
        $invoices = collect([
            [
                'id' => 'INV-001',
                'description' => 'Professional Plan - Monthly Subscription',
                'date' => '2025-01-01',
                'amount' => 299,
                'status' => 'paid',
            ],
            [
                'id' => 'INV-002',
                'description' => 'Professional Plan - Monthly Subscription',
                'date' => '2025-02-01',
                'amount' => 299,
                'status' => 'paid',
            ],
            [
                'id' => 'INV-003',
                'description' => 'Professional Plan - Monthly Subscription',
                'date' => '2025-03-01',
                'amount' => 299,
                'status' => 'pending',
            ],
        ]);

        return view('worker.billing.dashboard', [
            'company' => $company,
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show subscription upgrade page
     */
    public function upgrade(Request $request): View
    {
        $company = auth()->user()->company;
        $plan = $request->query('plan', 'professional');

        $plans = [
            'basic' => ['name' => 'Basic', 'price' => 99, 'features' => []],
            'professional' => ['name' => 'Professional', 'price' => 299, 'features' => []],
            'enterprise' => ['name' => 'Enterprise', 'price' => 0, 'features' => []],
        ];

        return view('worker.billing.upgrade', [
            'company' => $company,
            'selectedPlan' => $plan,
            'plans' => $plans,
        ]);
    }

    /**
     * Process subscription upgrade
     */
    public function processUpgrade(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:basic,professional,enterprise',
        ]);

        $company = auth()->user()->company;

        // TODO: Integrate with Stripe to process upgrade
        // For now, just update the company subscription tier
        $company->update([
            'subscription_tier' => $validated['plan'],
        ]);

        return redirect()->route('worker.billing')->with('success', 'Subscription upgraded successfully');
    }

    /**
     * Show invoices list
     */
    public function invoices(): View
    {
        $invoices = Payment::where('company_id', auth()->user()->company_id)
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('worker.billing.invoices', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice($id)
    {
        $invoice = Payment::where('company_id', auth()->user()->company_id)
                         ->findOrFail($id);

        // TODO: Generate and download PDF invoice
        return response()->download("invoice-{$id}.pdf");
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(Request $request)
    {
        $company = auth()->user()->company;

        // TODO: Integrate with Stripe to update payment method
        // This would typically involve showing a Stripe payment element

        return redirect()->back()->with('success', 'Payment method updated successfully');
    }
}
