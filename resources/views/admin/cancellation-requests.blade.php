@extends('layouts.admin')

@section('title', 'Cancellation Requests - Admin Portal')

@section('content')
<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Cancellation Requests</h1>
        <p class="text-gray-600">Review and manage subscription cancellation requests</p>
    </div>
    <div class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
        2 Pending
    </div>
</div>

<!-- Filter Tabs -->
<div class="flex gap-4 mb-6 border-b border-gray-200">
    <button class="px-4 py-3 font-medium text-blue-600 border-b-2 border-blue-600">All Requests</button>
    <button class="px-4 py-3 font-medium text-gray-600 hover:text-gray-900">Pending</button>
    <button class="px-4 py-3 font-medium text-gray-600 hover:text-gray-900">Approved</button>
    <button class="px-4 py-3 font-medium text-gray-600 hover:text-gray-900">Declined</button>
</div>

<!-- Cancellation Requests Table -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Client</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Plan</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date Requested</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Reason</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <!-- Request 1 -->
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div>
                        <p class="font-semibold text-slate-900">Sarah Johnson</p>
                        <p class="text-sm text-gray-600">sarah@company.com</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    <p class="font-medium text-slate-900">Premium Legal Advisory</p>
                    <p class="text-xs text-gray-600">$299/month</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">Feb 10, 2026</td>
                <td class="px-6 py-4 text-sm text-gray-600">Pricing concerns</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-semibold rounded-full">Pending</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <button onclick="openRequestModal(1)" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                        Review
                    </button>
                </td>
            </tr>

            <!-- Request 2 -->
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div>
                        <p class="font-semibold text-slate-900">Michael Chen</p>
                        <p class="text-sm text-gray-600">michael@startup.io</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    <p class="font-medium text-slate-900">Premium Legal Advisory</p>
                    <p class="text-xs text-gray-600">$299/month</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">Feb 08, 2026</td>
                <td class="px-6 py-4 text-sm text-gray-600">Switching providers</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-semibold rounded-full">Pending</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <button onclick="openRequestModal(2)" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                        Review
                    </button>
                </td>
            </tr>

            <!-- Request 3 -->
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div>
                        <p class="font-semibold text-slate-900">Emma Wilson</p>
                        <p class="text-sm text-gray-600">emma@business.co.uk</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    <p class="font-medium text-slate-900">Premium Legal Advisory</p>
                    <p class="text-xs text-gray-600">$299/month</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">Jan 25, 2026</td>
                <td class="px-6 py-4 text-sm text-gray-600">Service no longer required</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full">Approved</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <button onclick="openRequestModal(3)" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                        View
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Request Details Modal -->
<div id="requestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-900">Request Details</h2>
            <button onclick="closeRequestModal()" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Client Information -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4">Client Information</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Name</p>
                        <p class="font-semibold text-slate-900">Sarah Johnson</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Email</p>
                        <p class="font-semibold text-slate-900">sarah@company.com</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Account Type</p>
                        <p class="font-semibold text-slate-900">Business (B2B)</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Member Since</p>
                        <p class="font-semibold text-slate-900">Aug 15, 2025</p>
                    </div>
                </div>
            </div>

            <!-- Current Plan -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4">Current Plan</h3>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between mb-2">
                        <p class="font-semibold text-slate-900">Premium Legal Advisory</p>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Active</span>
                    </div>
                    <p class="text-sm text-gray-600">$299/month • Renewal: March 15, 2026</p>
                </div>
            </div>

            <!-- Request Details -->
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-4">Cancellation Request</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Date Requested</p>
                        <p class="font-semibold text-slate-900">Feb 10, 2026</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-1">Reason</p>
                        <p class="font-semibold text-slate-900">Pricing concerns</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 font-medium mb-2">Notes from Client</p>
                        <p class="text-slate-900">Looking for a more cost-effective solution that offers similar features. Would reconsider if pricing is adjusted.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3 pt-4 border-t border-gray-200">
                <form action="{{ route('admin.cancellation.approve', 1) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white font-semibold py-2.5 rounded-lg hover:bg-green-700 transition-colors">
                        Approve Cancellation
                    </button>
                </form>
                <form action="{{ route('admin.cancellation.decline', 1) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full border-2 border-red-600 text-red-600 font-semibold py-2.5 rounded-lg hover:bg-red-50 transition-colors">
                        Decline Cancellation
                    </button>
                </form>
                <button onclick="closeRequestModal()" class="w-full border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openRequestModal(id) {
    document.getElementById('requestModal').classList.remove('hidden');
}

function closeRequestModal() {
    document.getElementById('requestModal').classList.add('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRequestModal();
    }
});
</script>
@endsection
