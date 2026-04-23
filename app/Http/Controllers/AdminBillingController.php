<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBillingController extends Controller
{
    /**
     * Show admin billing dashboard
     */
    public function index(): View
    {
        // Calculate revenue metrics
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $monthlyRevenue = Payment::whereBetween('created_at', [$monthStart, $monthEnd])
                                ->where('status', 'paid')
                                ->sum('amount');

        $activeSubscriptions = User::where('is_subscribed', true)->count();

        $overdueInvoices = Payment::where('status', 'unpaid')
                                 ->where('due_date', '<', $now)
                                 ->count();

        // Calculate churn rate (users who cancelled in this month)
        $cancelledThisMonth = User::where('is_subscribed', false)
                                  ->whereNotNull('cancelled_at')
                                  ->whereBetween('cancelled_at', [$monthStart, $monthEnd])
                                  ->count();
        
        $churnRate = $activeSubscriptions > 0 ? round(($cancelledThisMonth / $activeSubscriptions) * 100, 2) : 0;

        // Get all subscriptions with pagination
        $subscriptions = User::where('is_subscribed', true)
                            ->orderBy('subscription_start_date', 'desc')
                            ->paginate(15);

        // Get all invoices with pagination
        $invoices = Payment::with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('billing.admin-index', [
            'monthlyRevenue' => $monthlyRevenue,
            'activeSubscriptions' => $activeSubscriptions,
            'overdueInvoices' => $overdueInvoices,
            'churnRate' => $churnRate,
            'subscriptions' => $subscriptions,
            'invoices' => $invoices,
        ]);
    }

    /**
     * Cancel user subscription (admin)
     */
    public function cancelSubscription(User $user)
    {
        if (!auth()->user()->hasRole(['admin', 'ceo'])) {
            abort(403);
        }

        $user->update([
            'is_subscribed' => false,
            'cancelled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscription cancelled');
    }

    /**
     * Mark invoice as paid
     */
    public function markInvoicePaid(Payment $invoice)
    {
        if (!auth()->user()->hasRole(['admin', 'ceo'])) {
            abort(403);
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Invoice marked as paid');
    }

    /**
     * Send payment reminder
     */
    public function sendPaymentReminder(Payment $invoice)
    {
        if (!auth()->user()->hasRole(['admin', 'ceo'])) {
            abort(403);
        }

        // TODO: Send email reminder to the invoice user
        // Mail::send(new PaymentReminderMail($invoice));

        return redirect()->back()->with('success', 'Reminder sent to client');
    }
}
