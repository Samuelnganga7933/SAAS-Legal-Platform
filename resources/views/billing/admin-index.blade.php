@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <!-- Revenue Overview Stats -->
    <div class="mb-10 grid grid-cols-4 gap-6">
        <div class="border-r border-gray-300 pr-6">
            <p class="text-sm text-gray-600 mb-2">Monthly revenue</p>
            <p class="text-3xl font-semibold text-gray-900">${{ number_format($monthlyRevenue ?? 0, 2) }}</p>
        </div>
        <div class="border-r border-gray-300 pr-6">
            <p class="text-sm text-gray-600 mb-2">Active subscriptions</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $activeSubscriptions ?? 0 }}</p>
        </div>
        <div class="border-r border-gray-300 pr-6">
            <p class="text-sm text-gray-600 mb-2">Overdue invoices</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $overdueInvoices ?? 0 }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-2">Churn this month</p>
            <p class="text-3xl font-semibold text-gray-900">{{ $churnRate ?? 0 }}%</p>
        </div>
    </div>

    <!-- All Subscriptions Table -->
    <div class="mb-10">
        <h2 class="text-base font-medium text-gray-900 mb-6">All subscriptions</h2>

        @if ($subscriptions->count() > 0)
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Client name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Plan</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Start date</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Next billing</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Monthly value</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($subscriptions as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ ucfirst($user->subscription_plan ?? 'Free') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $user->is_subscribed ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $user->is_subscribed ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->subscription_start_date?->format('M d, Y') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->next_billing_date?->format('M d, Y') ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-900 font-medium">${{ number_format($user->monthly_rate ?? 0, 2) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.clients') }}?search={{ $user->email }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    View client
                                </a>
                                @can('manage_billing')
                                <form action="{{ route('admin.subscriptions.cancel', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-900 text-sm">
                                        Cancel
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
        @else
        <p class="text-gray-600 text-sm py-8 text-center">No subscriptions found.</p>
        @endif
    </div>

    <!-- All Invoices Table -->
    <div>
        <h2 class="text-base font-medium text-gray-900 mb-6">All invoices</h2>

        @if ($invoices->count() > 0)
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Invoice #</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Client</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Amount</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Due date</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900 font-medium">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $invoice->user->name ?? 'Unknown' }}</td>
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
                        <td class="px-4 py-3 text-gray-600">{{ $invoice->due_date?->format('M d, Y') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($invoice->status !== 'paid')
                                <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">
                                        Mark paid
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.invoices.send-reminder', $invoice) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">
                                        Send reminder
                                    </button>
                                </form>
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
        <p class="text-gray-600 text-sm py-8 text-center">No invoices found.</p>
        @endif
    </div>
</div>
@endsection
