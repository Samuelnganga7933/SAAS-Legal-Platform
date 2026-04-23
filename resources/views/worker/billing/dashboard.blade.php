@extends('layouts.app')

@section('title', 'Billing & Subscription')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Billing & Subscription</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Current Plan Card -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Current Plan</h2>
                
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $company->subscription_tier === 'basic' ? 'Basic' : ($company->subscription_tier === 'professional' ? 'Professional' : 'Enterprise') }}
                        </span>
                        @if($company->hasActiveSubscription())
                            <span class="text-green-600 font-medium">✓ Active</span>
                        @else
                            <span class="text-red-600 font-medium">⚠️ Expired</span>
                        @endif
                    </div>
                    <p class="text-slate-600 text-sm">{{ $company->subscription_tier === 'basic' ? 'Perfect for solo practitioners' : ($company->subscription_tier === 'professional' ? 'For growing legal teams' : 'Enterprise solution with premium support') }}</p>
                </div>

                <div class="space-y-2 text-sm text-slate-600 mb-6">
                    <p><strong>Started:</strong> {{ $company->subscription_start->format('M d, Y') }}</p>
                    <p><strong>Renewal:</strong> {{ $company->subscription_end->format('M d, Y') }}</p>
                    <p><strong>Days Remaining:</strong> <strong class="text-slate-900">{{ now()->diffInDays($company->subscription_end, false) }}</strong></p>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <p class="text-xs text-slate-600 mb-3">Plan includes:</p>
                    <ul class="space-y-1 text-xs text-slate-600">
                        @if($company->subscription_tier === 'basic')
                            <li>✓ Up to 10 active cases</li>
                            <li>✓ Basic reporting</li>
                            <li>✓ Email support</li>
                            <li>✓ 1 team member</li>
                        @elseif($company->subscription_tier === 'professional')
                            <li>✓ Up to 50 active cases</li>
                            <li>✓ Advanced reporting</li>
                            <li>✓ Priority support</li>
                            <li>✓ Up to 5 team members</li>
                            <li>✓ Time tracking & billing</li>
                        @else
                            <li>✓ Unlimited cases</li>
                            <li>✓ Custom reporting</li>
                            <li>✓ Dedicated support</li>
                            <li>✓ Unlimited team members</li>
                            <li>✓ Video call integration</li>
                            <li>✓ Custom integrations</li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Pricing Plans Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Upgrade Plan</h2>
                
                <div class="space-y-2 mb-4">
                    @php
                        $plans = [
                            'basic' => ['name' => 'Basic', 'price' => '$99', 'period' => '/month'],
                            'professional' => ['name' => 'Professional', 'price' => '$299', 'period' => '/month'],
                            'enterprise' => ['name' => 'Enterprise', 'price' => 'Custom', 'period' => 'pricing']
                        ];
                    @endphp

                    @foreach($plans as $tier => $plan)
                        @if($tier !== $company->subscription_tier)
                            <div class="p-3 border border-slate-200 rounded-lg hover:border-blue-500 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $plan['name'] }}</p>
                                        <p class="text-sm text-slate-600">{{ $plan['price'] }} <span class="text-xs text-slate-500">{{ $plan['period'] }}</span></p>
                                    </div>
                                    <button class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-1 rounded text-sm font-medium transition">
                                        Upgrade
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <p class="text-xs text-slate-600 text-center mt-4">
                    Upgrade anytime. New plan takes effect immediately with prorated billing.
                </p>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Payment Method</h2>
            
            @if($company->stripe_customer_id)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <div>
                        <p class="text-sm font-medium text-slate-900">💳 Visa ending in <strong>4242</strong></p>
                        <p class="text-xs text-slate-600 mt-1">Expires 12/2025</p>
                    </div>
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">Update</button>
                </div>
            @else
                <div class="text-center p-6 bg-amber-50 rounded-lg border border-amber-200">
                    <p class="text-amber-900 mb-3">No payment method on file</p>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Add Payment Method
                    </button>
                </div>
            @endif
        </div>

        <!-- Invoice History -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Invoice History</h2>
            </div>

            @if($invoices && count($invoices) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-slate-900">Invoice</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-900">Description</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-900">Date</th>
                                <th class="px-6 py-3 text-right font-medium text-slate-900">Amount</th>
                                <th class="px-6 py-3 text-center font-medium text-slate-900">Status</th>
                                <th class="px-6 py-3 text-right font-medium text-slate-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900">#{{ $invoice['id'] }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $invoice['description'] ?? 'Subscription' }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $invoice['date'] }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-slate-900">${{ number_format($invoice['amount'], 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block {{ $invoice['status'] === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} px-2 py-1 rounded text-xs font-medium">
                                            {{ ucfirst($invoice['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Download PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-slate-600 mb-3">No invoices yet</p>
                    <p class="text-sm text-slate-500">Your invoices will appear here as they're generated</p>
                </div>
            @endif
        </div>

        <!-- Billing FAQ -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-slate-900 mb-3">📋 Billing FAQ</h3>
            <ul class="space-y-2 text-sm text-slate-700">
                <li><strong>Billing Cycle:</strong> Monthly subscriptions renew on the same date each month</li>
                <li><strong>Cancellation:</strong> You can cancel anytime. Access continues until the end of your billing cycle</li>
                <li><strong>Overage Charges:</strong> Professional/Enterprise plans include case limits. Contact support for usage above limits</li>
                <li><strong>Refunds:</strong> No refunds on subscriptions, but credits apply to future invoices</li>
            </ul>
            <button class="mt-4 text-blue-600 hover:text-blue-700 font-medium text-sm">Contact Billing Support →</button>
        </div>
    </div>
</div>
@endsection
