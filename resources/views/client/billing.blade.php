@extends('layouts.client')

@section('title', 'Billing & Membership - Client Portal')

@section('content')
<style>
    .section-card {
        background-color: var(--color-card-surface);
        border: 1px solid var(--color-border);
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .btn-primary {
        background-color: var(--color-primary-blue);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 14px;
    }
    .btn-primary:hover {
        opacity: 0.9;
    }
    .btn-secondary {
        background-color: var(--color-card-surface);
        color: var(--color-secondary-text);
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 14px;
    }
    .btn-secondary:hover {
        background-color: #F1F5F9;
    }
    .btn-danger {
        background-color: var(--color-card-surface);
        color: var(--color-danger);
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid var(--color-danger);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 14px;
    }
    .btn-danger:hover {
        background-color: #FEF2F2;
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-active {
        background-color: #ECFDF5;
        color: var(--color-success);
    }
    .status-paid {
        background-color: #ECFDF5;
        color: var(--color-success);
    }
    .status-pending {
        background-color: #FFFBEB;
        color: #B45309;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead tr {
        background-color: #F1F5F9;
        border-bottom: 1px solid var(--color-border);
    }
    thead th {
        color: var(--color-secondary-text);
        text-align: left;
        padding: 12px 24px;
        font-size: 13px;
        font-weight: 600;
    }
    tbody tr {
        border-bottom: 1px solid var(--color-border);
        transition: all 0.15s ease;
    }
    tbody tr:hover {
        background-color: #F1F5F9;
    }
    tbody td {
        color: var(--color-dark-text);
        padding: 16px 24px;
        font-size: 14px;
    }
    .action-link {
        color: var(--color-primary-blue);
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }
    .action-link:hover {
        opacity: 0.8;
    }
</style>

<!-- Page Header -->
<div>
    <h1 style="color: var(--color-dark-text); font-size: 30px; font-weight: 700; margin-bottom: 8px;">Billing & Membership</h1>
    <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 0;">Manage your plan, payments, and invoices</p>
</div>

<!-- Section 1: Current Plan (Highlighted) -->
<div class="section-card" style="background: linear-gradient(135deg, #EFF6FF 0%, #F0F9FF 100%); border: 1px solid #BFDBFE; margin-top: 32px;">
    <div style="display: flex; align-items: flex-start; justify-content: space-between;">
        <div>
            <h2 style="color: var(--color-dark-text); font-size: 24px; font-weight: 700; margin-bottom: 8px;">Premium Legal Advisory</h2>
            <div style="margin-bottom: 16px;">
                <span class="status-badge status-active">Active</span>
                <p style="color: var(--color-secondary-text); margin-top: 12px; font-size: 14px;">Next renewal: <span style="color: var(--color-dark-text); font-weight: 600;">March 15, 2026</span></p>
            </div>
            <p style="color: var(--color-dark-text); font-size: 16px; font-weight: 600;">$299 / month</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="btn-secondary" style="margin: 0;">Manage Plan</button>
            <button class="btn-primary" style="margin: 0;">Upgrade Plan</button>
        </div>
    </div>
</div>

<!-- Section 2: Payment Method -->
<div class="section-card">
    <h3 style="color: var(--color-dark-text); font-size: 18px; font-weight: 700; margin-bottom: 16px;">Payment Method</h3>
    <div style="background-color: #F1F5F9; border-radius: 10px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <svg style="width: 32px; height: 32px; color: var(--color-secondary-text);" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 10h18M7 15h.01M11 15h.01M15 15h.01M4 6h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
            </svg>
            <div>
                <p style="color: var(--color-dark-text); font-weight: 600;">Credit Card</p>
                <p style="color: var(--color-secondary-text); font-size: 14px;">•••• •••• •••• 4242</p>
                <p style="color: var(--color-secondary-text); font-size: 13px;">Expires 12/2027</p>
            </div>
        </div>
        <button class="btn-secondary">Update Method</button>
    </div>
</div>

<!-- Section 3: Invoice History -->
<div class="section-card">
    <h3 style="color: var(--color-dark-text); font-size: 18px; font-weight: 700; margin-bottom: 16px;">Invoice History</h3>
    <div style="background-color: var(--color-card-surface); border: 1px solid var(--color-border); border-radius: 10px; overflow: hidden;">
        <table>
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align: right;">Download</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">INV-2026-0045</td>
                    <td style="color: var(--color-secondary-text);">Feb 1, 2026</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$299.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">PDF</a></td>
                </tr>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">INV-2026-0044</td>
                    <td style="color: var(--color-secondary-text);">Jan 1, 2026</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$299.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">PDF</a></td>
                </tr>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">INV-2025-0043</td>
                    <td style="color: var(--color-secondary-text);">Dec 1, 2025</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$299.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">PDF</a></td>
                </tr>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">INV-2025-0042</td>
                    <td style="color: var(--color-secondary-text);">Nov 1, 2025</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$199.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">PDF</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Section 4: One-Time Purchases -->
<div class="section-card">
    <h3 style="color: var(--color-dark-text); font-size: 18px; font-weight: 700; margin-bottom: 16px;">One-Time Purchases</h3>
    <div style="background-color: var(--color-card-surface); border: 1px solid var(--color-border); border-radius: 10px; overflow: hidden;">
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align: right;">Invoice</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">Contract Review</td>
                    <td style="color: var(--color-secondary-text);">Jan 18, 2026</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$120.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">INV-2026-0040</a></td>
                </tr>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">Compliance Review</td>
                    <td style="color: var(--color-secondary-text);">Dec 10, 2025</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$85.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">INV-2025-0039</a></td>
                </tr>
                <tr>
                    <td style="color: var(--color-dark-text); font-weight: 600;">Document Template Pack</td>
                    <td style="color: var(--color-secondary-text);">Nov 5, 2025</td>
                    <td style="color: var(--color-dark-text); font-weight: 600;">$50.00</td>
                    <td><span class="status-badge status-paid">Paid</span></td>
                    <td style="text-align: right;"><a href="#" class="action-link">INV-2025-0038</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Danger Zone: Cancellation -->
<div class="section-card" style="border: 1px solid var(--color-danger);">
    <h3 style="color: var(--color-danger); font-size: 18px; font-weight: 700; margin-bottom: 8px;">Subscription Cancellation</h3>
    <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 16px;">Need to cancel your subscription? We understand. You can submit a cancellation request below.</p>
    <a href="{{ route('membership.cancel') }}" class="btn-danger">Submit Cancellation Request</a>
</div>
@endsection
