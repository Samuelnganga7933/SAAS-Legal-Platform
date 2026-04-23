@extends('layouts.admin')

@section('title', 'Settings - Admin Portal')

@section('content')
<!-- Page Header -->
<div>
    <h1 class="text-3xl font-bold text-slate-909 mb-2">System Settings</h1>
    <p class="text-gray-600">Configure system-wide settings and preferences</p>
</div>

<!-- Settings Tabs -->
<div class="mt-8 flex gap-4 border-b border-gray-200">
    <button class="px-4 py-3 font-medium text-blue-600 border-b-2 border-blue-600">General</button>
    <button class="px-4 py-3 font-medium text-gray-600 hover:text-gray-900">Email Configuration</button>
    <button class="px-4 py-3 font-medium text-gray-600 hover:text-gray-900">Security</button>
</div>

<!-- General Settings -->
<div class="mt-8 bg-white rounded-lg border border-gray-200 p-8 max-w-2xl">
    <h2 class="text-xl font-bold text-slate-909 mb-6">General Settings</h2>
    
    <form class="space-y-6">
        <!-- Site Name -->
        <div>
            <label class="block text-sm font-semibold text-slate-909 mb-2">Site Name</label>
            <input type="text" value="Le Nium Legal" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <!-- Support Email -->
        <div>
            <label class="block text-sm font-semibold text-slate-909 mb-2">Support Email</label>
            <input type="email" value="leniumtradinggroup@outlook.com" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <!-- WhatsApp Business Number -->
        <div>
            <label class="block text-sm font-semibold text-slate-909 mb-2">WhatsApp Business Number</label>
            <input type="tel" value="+254 104 921 009" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <!-- Default Plan -->
        <div>
            <label class="block text-sm font-semibold text-slate-909 mb-2">Default Subscription Plan</label>
            <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                <option>Premium Legal Advisory - $299/month</option>
                <option>Standard Plan - $99/month</option>
            </select>
        </div>

        <!-- Save Button -->
        <div class="pt-4">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Security Settings -->
<div class="mt-8 bg-white rounded-lg border border-gray-200 p-8 max-w-2xl">
    <h2 class="text-xl font-bold text-slate-900 mb-6">Security Settings</h2>
    
    <!-- 2FA Recommendation Notice -->
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg flex gap-3">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="font-semibold text-amber-900 text-sm">Admin Security</p>
            <p class="text-xs text-amber-800 mt-1">As an administrator, enabling Two-Factor Authentication (2FA) is critical to protect sensitive system data and user accounts. Please enable 2FA on your account immediately.</p>
        </div>
    </div>

    <!-- Two-Factor Authentication -->
    <div class="pb-6 border-b border-gray-200">
        <p class="font-semibold text-slate-900 mb-2">Two-Factor Authentication</p>
        <p class="text-sm text-gray-600 mb-4">Add an extra layer of security to your administrator account</p>
        <button type="button" class="px-4 py-2.5 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors text-sm">
            Enable 2FA
        </button>
    </div>

    <!-- Change Password -->
    <div class="pt-6">
        <p class="font-semibold text-slate-900 mb-2">Change Password</p>
        <p class="text-sm text-gray-600 mb-4">Update your administrator password regularly</p>
        <button type="button" class="px-4 py-2.5 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-colors text-sm">
            Change Password
        </button>
    </div>
</div>
@endsection
