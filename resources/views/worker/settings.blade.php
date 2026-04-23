@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Settings</h1>

        <!-- Settings Tabs -->
        <div class="flex gap-2 mb-6 border-b border-slate-200">
            <button class="px-4 py-3 border-b-2 border-blue-600 text-blue-600 font-medium transition" data-tab="profile">
                👤 Profile
            </button>
            <button class="px-4 py-3 border-b-2 border-transparent text-slate-600 hover:text-slate-900 font-medium transition" data-tab="availability">
                📅 Availability & Rates
            </button>
            <button class="px-4 py-3 border-b-2 border-transparent text-slate-600 hover:text-slate-900 font-medium transition" data-tab="security">
                🔒 Security
            </button>
            <button class="px-4 py-3 border-b-2 border-transparent text-slate-600 hover:text-slate-900 font-medium transition" data-tab="notifications">
                🔔 Notifications
            </button>
        </div>

        <!-- Profile Tab -->
        <div id="profile" class="tab-content">
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">Profile Information</h2>

                <form method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                        <!-- Profile Picture -->
                        <div class="flex flex-col items-center">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-3xl font-bold mb-4">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <button type="button" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Change Photo</button>
                            <input type="file" accept="image/*" class="hidden" id="photo">
                        </div>

                        <!-- Profile Form -->
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-900 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-900 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-900 mb-2">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ auth()->user()->phone ?? '' }}" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-slate-900 mb-2">Professional Bio</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell clients about your expertise...">{{ auth()->user()->bio ?? '' }}</textarea>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Save Changes
                        </button>
                        <button type="button" class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-2 rounded-lg font-medium transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Availability & Rates Tab -->
        <div id="availability" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">Availability & Billing Rates</h2>

                <form method="POST" action="#">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-slate-900 mb-2">Default Hourly Rate ($)</label>
                            <input type="number" name="hourly_rate" id="hourly_rate" value="{{ auth()->user()->hourly_rate ?? '150' }}" min="0" step="0.01" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-slate-600 mt-1">Used for time billing by default</p>
                        </div>

                        <div>
                            <label for="availability_hours" class="block text-sm font-medium text-slate-900 mb-2">Weekly Availability Hours</label>
                            <input type="number" name="availability_hours" id="availability_hours" value="{{ auth()->user()->availability_hours ?? '40' }}" min="0" max="168" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-slate-600 mt-1">How many hours you work per week</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-900 mb-3">Work Schedule</label>
                        <div class="space-y-2">
                            @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; @endphp
                            @foreach($days as $day)
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="work_days[]" id="day_{{ strtolower($day) }}" value="{{ strtolower($day) }}" {{ in_array(strtolower($day), ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']) ? 'checked' : '' }} class="w-4 h-4 border-slate-300 rounded text-blue-600">
                                    <label for="day_{{ strtolower($day) }}" class="text-sm text-slate-900">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-900 mb-3">Time Zone</label>
                        <select name="timezone" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="America/New_York" selected>Eastern Time</option>
                            <option value="America/Chicago">Central Time</option>
                            <option value="America/Denver">Mountain Time</option>
                            <option value="America/Los_Angeles">Pacific Time</option>
                            <option value="America/Anchorage">Alaska Time</option>
                            <option value="America/Honolulu">Hawaii Time</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Save Changes
                        </button>
                        <button type="button" class="bg-slate-200 hover:bg-slate-300 text-slate-900 px-6 py-2 rounded-lg font-medium transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Tab -->
        <div id="security" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow p-8 mb-6">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">Change Password</h2>

                <form method="POST" action="#">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 max-w-md">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-slate-900 mb-2">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-slate-900 mb-2">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-slate-600 mt-1">At least 8 characters, with uppercase and numbers</p>
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-slate-900 mb-2">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">Two-Factor Authentication</h2>

                <div class="mb-6">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div>
                            <p class="font-medium text-slate-900">2FA Status</p>
                            <p class="text-sm text-slate-600 mt-1">Protect your account with two-factor authentication</p>
                        </div>
                        <button type="button" class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-4 py-2 rounded-lg font-medium transition">
                            Enable 2FA
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Tab -->
        <div id="notifications" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-xl font-semibold text-slate-900 mb-6">Notification Preferences</h2>

                <form method="POST" action="#">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded">
                            <div>
                                <p class="font-medium text-slate-900">New Case Assigned</p>
                                <p class="text-sm text-slate-600">Get notified when you're assigned to a new case</p>
                            </div>
                            <input type="checkbox" name="notify_case_assigned" checked class="w-4 h-4 border-slate-300 rounded text-blue-600">
                        </div>

                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded">
                            <div>
                                <p class="font-medium text-slate-900">Task Reminders</p>
                                <p class="text-sm text-slate-600">Remind me of upcoming task deadlines</p>
                            </div>
                            <input type="checkbox" name="notify_task_reminder" checked class="w-4 h-4 border-slate-300 rounded text-blue-600">
                        </div>

                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded">
                            <div>
                                <p class="font-medium text-slate-900">New Messages</p>
                                <p class="text-sm text-slate-600">Notify me of new messages from clients and team</p>
                            </div>
                            <input type="checkbox" name="notify_messages" checked class="w-4 h-4 border-slate-300 rounded text-blue-600">
                        </div>

                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded">
                            <div>
                                <p class="font-medium text-slate-900">Document Uploads</p>
                                <p class="text-sm text-slate-600">Notify me when clients upload new documents</p>
                            </div>
                            <input type="checkbox" name="notify_documents" class="w-4 h-4 border-slate-300 rounded text-blue-600">
                        </div>

                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded">
                            <div>
                                <p class="font-medium text-slate-900">Billing Alerts</p>
                                <p class="text-sm text-slate-600">Alerts about subscription and billing events</p>
                            </div>
                            <input type="checkbox" name="notify_billing" checked class="w-4 h-4 border-slate-300 rounded text-blue-600">
                        </div>

                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded border-t border-slate-200 pt-4">
                            <div>
                                <p class="font-medium text-slate-900">Email Notifications</p>
                                <p class="text-sm text-slate-600">Receive emails of important events</p>
                            </div>
                            <input type="checkbox" name="email_notifications" checked class="w-4 h-4 border-slate-300 rounded text-blue-600">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-tab]').forEach(button => {
        button.addEventListener('click', (e) => {
            const tabName = e.target.dataset.tab;
            
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            
            // Remove active state from all buttons
            document.querySelectorAll('[data-tab]').forEach(btn => btn.classList.remove('border-blue-600', 'text-blue-600'));
            
            // Show selected tab
            document.getElementById(tabName).classList.remove('hidden');
            
            // Add active state to button
            e.target.classList.add('border-blue-600', 'text-blue-600');
        });
    });
</script>
@endsection
