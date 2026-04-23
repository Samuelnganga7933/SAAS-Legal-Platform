@extends('layouts.app')

@section('title', 'Payment Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Payment Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage and track all payments</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPayments ?? 0 }}</p>
                </div>
                <svg class="h-12 w-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($totalRevenue ?? 0, 2) }}</p>
                </div>
                <svg class="h-12 w-12 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.16 5.314l4.897 4.896a1 1 0 001.414-1.414L9.574 3.9A1 1 0 008.5 3.5H2a1 1 0 00-1 1v6a1 1 0 001 1h4a1 1 0 00.414-.086l6.328-6.328A1 1 0 0011.328 4.086L8.16 5.314z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Successful Payments</p>
                    <p class="text-2xl font-bold text-green-600">{{ $successfulPayments ?? 0 }}</p>
                </div>
                <svg class="h-12 w-12 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Failed Payments</p>
                    <p class="text-2xl font-bold text-red-600">{{ $failedPayments ?? 0 }}</p>
                </div>
                <svg class="h-12 w-12 text-red-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="switchTab('recent-payments')" class="tab-button py-4 px-1 border-b-2 border-blue-600 font-medium text-sm text-blue-600" data-tab="recent-payments">
                    Recent Payments
                </button>
                <button onclick="switchTab('payment-requests')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="payment-requests">
                    Payment Requests
                </button>
                <button onclick="switchTab('refunds')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="refunds">
                    Refunds
                </button>
            </nav>
        </div>

        <!-- Recent Payments Tab -->
        <div id="recent-payments" class="tab-content p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Payment ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Customer</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Amount</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Type</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentPayments ?? [] as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs">{{ substr($payment->stripe_payment_intent_id, 0, 20) }}...</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $payment->customer_name }}</div>
                                        <div class="text-gray-500">{{ $payment->customer_email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ ucfirst($payment->payment_type) }}</td>
                                <td class="px-6 py-4">
                                    @if($payment->status === 'succeeded')
                                        <span class="inline-flex px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Succeeded</span>
                                    @elseif($payment->status === 'failed')
                                        <span class="inline-flex px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Failed</span>
                                    @elseif($payment->status === 'refunded')
                                        <span class="inline-flex px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Refunded</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Requests Tab -->
        <div id="payment-requests" class="tab-content hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Request ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Amount</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Expires</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($paymentRequests ?? [] as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">#{{ $request->id }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $request->email }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($request->amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($request->status === 'paid')
                                        <span class="inline-flex px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Paid</span>
                                    @elseif($request->status === 'expired')
                                        <span class="inline-flex px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Expired</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ ucfirst($request->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $request->expires_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No payment requests found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Refunds Tab -->
        <div id="refunds" class="tab-content hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Original Payment ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Customer</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Refund Amount</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Refund Date</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($refunds ?? [] as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs">{{ substr($payment->stripe_payment_intent_id, 0, 20) }}...</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $payment->customer_name }}</div>
                                        <div class="text-gray-500">{{ $payment->customer_email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($payment->refunded_amount, 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $payment->refunded_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium text-sm">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No refunds found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');

        // Update button styles
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        document.querySelector(`[data-tab="${tabName}"]`).classList.remove('border-transparent', 'text-gray-500');
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('border-blue-600', 'text-blue-600');
    }
</script>
@endsection
