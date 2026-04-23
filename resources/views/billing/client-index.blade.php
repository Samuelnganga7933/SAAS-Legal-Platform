@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Billing</h1>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        {{ session('error') }}
    </div>
    @endif

    <!-- Current Plan Section -->
    <div class="mb-10">
        <h2 class="text-base font-medium text-gray-900 mb-6">Your plan</h2>

        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Plan name</span>
                <span class="font-medium text-gray-900">{{ auth()->user()->subscription_plan ?? 'Free' }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Status</span>
                <span class="font-medium {{ auth()->user()->is_subscribed ? 'text-green-600' : 'text-gray-600' }}">
                    {{ auth()->user()->is_subscribed ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Billing cycle</span>
                <span class="font-medium text-gray-900">Monthly</span>
            </div>
            @if (auth()->user()->next_billing_date)
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Next billing date</span>
                <span class="font-medium text-gray-900">{{ auth()->user()->next_billing_date->format('M d, Y') }}</span>
            </div>
            @endif
            <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Amount</span>
                <span class="font-medium text-gray-900">${{ auth()->user()->monthly_rate ?? '0' }}/month</span>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <a href="{{ route('client.upgrade-plan') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                Upgrade plan
            </a>
            @if (auth()->user()->is_subscribed)
            <form action="{{ route('client.cancel-subscription') }}" method="POST" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure?')" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    Cancel subscription
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Invoice History Section -->
    <div class="mb-10">
        <h2 class="text-base font-medium text-gray-900 mb-6">Invoices</h2>

        @if ($invoices->count() > 0)
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Invoice #</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Date</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Amount</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $invoice->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-900 font-medium">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ match($invoice->status) {
                                'paid' => 'bg-green-100 text-green-700',
                                'unpaid' => 'bg-red-100 text-red-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                default => 'bg-gray-100 text-gray-700'
                            } }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('client.download-invoice', $invoice) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    Download PDF
                                </a>
                                @if ($invoice->status === 'unpaid')
                                <a href="{{ route('client.pay-invoice', $invoice) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    Pay now
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
        @else
        <p class="text-gray-600 text-sm py-8 text-center">No invoices yet.</p>
        @endif
    </div>

    <!-- Payment Method Section -->
    <div>
        <h2 class="text-base font-medium text-gray-900 mb-6">Payment method</h2>

        @if (auth()->user()->stripe_card_last_four)
        <div class="mb-6">
            <p class="text-sm text-gray-600">
                {{ auth()->user()->stripe_card_brand ?? 'Card' }} ending in {{ auth()->user()->stripe_card_last_four }}
                <span class="text-gray-500">· Expires {{ auth()->user()->stripe_card_expiry }}</span>
            </p>
        </div>
        <a href="{{ route('client.update-payment-method') }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Update card
        </a>
        @else
        <p class="text-sm text-gray-600 mb-4">No payment method on file.</p>
        <a href="{{ route('client.add-payment-method') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
            Add payment method
        </a>
        @endif
    </div>
</div>
@endsection
