@extends('layouts.admin')

@section('title', 'Billing - Admin Portal')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-909 mb-2">Billing & Revenue</h1>
    <p class="text-gray-600">Track payments and subscription revenue</p>
</div>

<!-- Revenue Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <p class="text-gray-600 text-sm font-medium mb-2">Monthly Revenue</p>
        <p class="text-3xl font-bold text-slate-900">$14,352</p>
        <p class="text-xs text-gray-500 mt-2">+12% from last month</p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <p class="text-gray-600 text-sm font-medium mb-2">Active Subscriptions</p>
        <p class="text-3xl font-bold text-slate-900">48</p>
        <p class="text-xs text-gray-500 mt-2">Premium plans</p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <p class="text-gray-600 text-sm font-medium mb-2">Pending Invoices</p>
        <p class="text-3xl font-bold text-slate-909">$2,997</p>
        <p class="text-xs text-gray-500 mt-2">3 unpaid invoices</p>
    </div>
</div>

<!-- Recent Invoices -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Invoice ID</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Client</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Amount</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-909">INV-2026-045</td>
                <td class="px-6 py-4 text-sm text-gray-600">James M.</td>
                <td class="px-6 py-4 text-sm font-semibold">$299</td>
                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Paid</span></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
