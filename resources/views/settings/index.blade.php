@extends('layouts.admin')

@section('content')
<div class="max-w-6xl">
    <h1 class="text-2xl font-semibold text-gray-900 mb-8">Settings</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Left navigation -->
        <div class="md:col-span-1">
            <nav class="space-y-2">
                <a href="#profile" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-900 bg-gray-100" data-section="profile">Profile</a>
                <a href="#password" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50" data-section="password">Password</a>
                <a href="#notifications" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50" data-section="notifications">Notifications</a>
                <a href="#appearance" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50" data-section="appearance">Appearance</a>
                <a href="#billing" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50" data-section="billing">Billing</a>
                @if(auth()->user()->isCEO())
                <a href="#integrations" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50" data-section="integrations">Integrations</a>
                @endif
                <a href="#danger" class="settings-nav block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50" data-section="danger">Danger zone</a>
            </nav>
        </div>

        <!-- Right content -->
        <div class="md:col-span-3 space-y-8">
            @if ($errors->any())
            <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                <ul class="mt-2 text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Profile Section -->
            <section id="profile" class="settings-section">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Profile</h2>
                <form action="{{ route('settings.update-profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Job title</label>
                            <input type="text" name="job_title" value="{{ old('job_title', auth()->user()->job_title) }}" placeholder="e.g., Senior Attorney" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Profile photo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if(auth()->user()->profile_photo_path)
                                <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover" />
                                @else
                                <span class="text-gray-400 text-2xl">👤</span>
                                @endif
                            </div>
                            <input type="file" name="profile_photo" accept="image/*" class="block text-sm text-gray-500" />
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Save profile
                    </button>
                </form>
            </section>

            <!-- Password Section -->
            <section id="password" class="settings-section hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Change password</h2>
                <form action="{{ route('settings.update-password') }}" method="POST" class="space-y-6 max-w-md">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                        <input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        <p class="text-xs text-gray-500 mt-1">At least 8 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                        <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Update password
                    </button>
                </form>
            </section>

            <!-- Notifications Section -->
            <section id="notifications" class="settings-section hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Notification preferences</h2>
                <form action="{{ route('settings.update-notifications') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="notify_case_assigned" value="1" {{ auth()->user()->notify_case_assigned ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            <div>
                                <div class="text-sm font-medium text-gray-900">Case assigned</div>
                                <div class="text-xs text-gray-500">Get notified when a new case is assigned to you</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="notify_message" value="1" {{ auth()->user()->notify_message ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            <div>
                                <div class="text-sm font-medium text-gray-900">New message</div>
                                <div class="text-xs text-gray-500">Get notified when you receive a new message</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="notify_payment" value="1" {{ auth()->user()->notify_payment ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            <div>
                                <div class="text-sm font-medium text-gray-900">Payment received</div>
                                <div class="text-xs text-gray-500">Get notified when a payment is received</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="notify_deadline" value="1" {{ auth()->user()->notify_deadline ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            <div>
                                <div class="text-sm font-medium text-gray-900">Upcoming deadline</div>
                                <div class="text-xs text-gray-500">Get notified 24 hours before an event or deadline</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="notify_team_activity" value="1" {{ auth()->user()->notify_team_activity ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            <div>
                                <div class="text-sm font-medium text-gray-900">Team activity</div>
                                <div class="text-xs text-gray-500">Get notified of team member activities</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="notify_weekly_digest" value="1" {{ auth()->user()->notify_weekly_digest ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            <div>
                                <div class="text-sm font-medium text-gray-900">Weekly digest</div>
                                <div class="text-xs text-gray-500">Receive a weekly summary every Monday morning</div>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Save preferences
                    </button>
                </form>
            </section>

            <!-- Appearance Section -->
            <section id="appearance" class="settings-section hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Appearance</h2>
                <form action="{{ route('settings.update-appearance') }}" method="POST" class="space-y-6 max-w-md">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Theme</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="theme" value="light" {{ auth()->user()->theme === 'light' || !auth()->user()->theme ? 'checked' : '' }} class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-600" />
                                <span class="text-sm text-gray-700">Light</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="theme" value="dark" {{ auth()->user()->theme === 'dark' ? 'checked' : '' }} class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-600" />
                                <span class="text-sm text-gray-700">Dark</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="theme" value="auto" {{ auth()->user()->theme === 'auto' ? 'checked' : '' }} class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-600" />
                                <span class="text-sm text-gray-700">Auto (system preference)</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Save theme
                    </button>
                </form>
            </section>

            <!-- Billing Section -->
            <section id="billing" class="settings-section hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Billing</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Current plan</h3>
                        <div class="border rounded-lg p-4">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium capitalize">{{ auth()->user()->subscription_plan ?? 'Basic' }}&nbsp;Plan</span>
                                @if(auth()->user()->subscription_start_date && auth()->user()->subscription_start_date->isFuture())
                                <span class="text-xs text-blue-600">Renews {{ auth()->user()->next_billing_date?->format('M d, Y') ?? 'N/A' }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('billing.upgrade') }}" class="inline-block px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                Upgrade or change plan
                            </a>
                            @if(auth()->user()->is_subscribed)
                            <form action="{{ route('billing.cancel-subscription') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure? Your subscription will end at the next billing date.')" class="px-4 py-2 text-sm font-medium text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                    Cancel subscription
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Payment method</h3>
                        @if(auth()->user()->stripe_card_last_four)
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <p class="text-sm text-gray-600">
                                •••• •••• •••• <span class="font-medium">{{ auth()->user()->stripe_card_last_four }}</span>
                                <span class="text-xs text-gray-500">Expires {{ auth()->user()->stripe_card_expires }}</span>
                            </p>
                        </div>
                        @else
                        <p class="text-sm text-gray-500">No payment method on file</p>
                        @endif
                    </div>

                    <a href="{{ route('billing.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                        View full billing history →
                    </a>
                </div>
            </section>

            <!-- Integrations Section (CEO only) -->
            @if(auth()->user()->isCEO())
            <section id="integrations" class="settings-section hidden">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Integrations</h2>
                <form action="{{ route('settings.update-integrations') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="border rounded-lg p-4 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Google Calendar</h3>
                                <p class="text-xs text-gray-500 mt-1">Sync events and deadlines</p>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="google_calendar_connected" value="1" {{ auth()->user()->google_calendar_connected ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            </label>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Stripe</h3>
                                <p class="text-xs text-gray-500 mt-1">Process payments and manage subscriptions</p>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="stripe_connected" value="1" {{ auth()->user()->stripe_connected ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" disabled />
                            </label>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Google Ads</h3>
                                <p class="text-xs text-gray-500 mt-1">Track and manage ad campaigns</p>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="google_ads_connected" value="1" {{ auth()->user()->google_ads_connected ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            </label>
                        </div>
                    </div>

                    <div class="border rounded-lg p-4 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Meta Ads</h3>
                                <p class="text-xs text-gray-500 mt-1">Track and manage Facebook/Instagram ads</p>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="meta_ads_connected" value="1" {{ auth()->user()->meta_ads_connected ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" />
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                        Save integrations
                    </button>
                </form>
            </section>
            @endif

            <!-- Danger Zone Section -->
            <section id="danger" class="settings-section hidden border-t-2 border-red-200 pt-8">
                <h2 class="text-lg font-semibold text-red-900 mb-6">Danger zone</h2>
                <div class="space-y-6">
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <h3 class="text-sm font-semibold text-red-900">Delete account</h3>
                        <p class="text-sm text-red-700 mt-2">Permanently delete your account and all associated data. This action cannot be undone.</p>
                        <form action="{{ route('settings.delete-account') }}" method="POST" class="mt-4" onsubmit="return confirm('Are you absolutely sure? This will delete your account and all data permanently.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                Delete my account
                            </button>
                        </form>
                    </div>

                    @if(auth()->user()->isCEO())
                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                        <h3 class="text-sm font-semibold text-red-900">Reset platform</h3>
                        <p class="text-sm text-red-700 mt-2">Reset all data and configuration. This will delete all cases, contacts, and team members. This action cannot be undone.</p>
                        <form action="{{ route('settings.reset-platform') }}" method="POST" class="mt-4" onsubmit="return confirm('Are you absolutely sure? This will delete ALL data on the platform.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                Reset platform
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.settings-nav').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = e.target.getAttribute('data-section');

            // Hide all sections
            document.querySelectorAll('.settings-section').forEach(s => s.classList.add('hidden'));

            // Update nav styles
            document.querySelectorAll('.settings-nav').forEach(nav => {
                nav.classList.remove('bg-gray-100', 'text-gray-900');
                nav.classList.add('text-gray-600', 'hover:bg-gray-50');
            });

            // Show selected section
            const showSection = document.getElementById(section);
            if (showSection) {
                showSection.classList.remove('hidden');
                e.target.classList.remove('text-gray-600', 'hover:bg-gray-50');
                e.target.classList.add('bg-gray-100', 'text-gray-900');
            }
        });
    });
</script>
@endsection
