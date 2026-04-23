@extends('layouts.client')

@section('title', 'Settings - Client Portal')

@section('content')
<style>
    .section-card {
        background-color: var(--color-card-surface);
        border: 1px solid var(--color-border);
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 24px;
        max-width: 600px;
    }
    .tab-button {
        padding: 12px 0;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        color: var(--color-secondary-text);
        border-bottom: 3px solid transparent;
        margin-right: 24px;
        transition: all 0.15s ease;
    }
    .tab-button.active {
        color: var(--color-primary-blue);
        border-bottom-color: var(--color-primary-blue);
    }
    .tab-button:hover {
        color: var(--color-dark-text);
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
        background-color: var(--color-danger);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 14px;
    }
    .btn-danger:hover {
        opacity: 0.9;
    }
    input:disabled {
        background-color: #F1F5F9;
        color: var(--color-secondary-text);
        cursor: not-allowed;
    }
</style>

<!-- Page Header -->
<div>
    <h1 style="color: var(--color-dark-text); font-size: 30px; font-weight: 700; margin-bottom: 8px;">Account Settings</h1>
    <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 0;">Manage your profile and account preferences</p>
</div>

<!-- Settings Tabs -->
<div style="margin-top: 32px; border-bottom: 1px solid var(--color-border); margin-bottom: 32px;">
    <button class="tab-button active" onclick="showTab('profile')">Profile</button>
    <button class="tab-button" onclick="showTab('security')">Security</button>
    <button class="tab-button" onclick="showTab('preferences')">Preferences</button>
</div>

<!-- Profile Settings Tab -->
<div id="profile" class="tab-content">
    <div class="section-card">
        <h2 style="color: var(--color-dark-text); font-size: 20px; font-weight: 700; margin-bottom: 24px;">Profile Information</h2>
        
        <form action="{{ route('settings.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 24px;">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div style="display: flex; gap: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--color-border);">
                <div>
                    <div style="background: linear-gradient(135deg, var(--color-soft-blue), var(--color-primary-blue)); border-radius: 50%; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 24px;">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
                <div style="flex: 1;">
                    <p style="color: var(--color-dark-text); font-weight: 600; margin-bottom: 8px;">Profile Photo</p>
                    <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 12px;">JPG, PNG or GIF (Max 5MB)</p>
                    <button type="button" class="btn-secondary">Upload Photo</button>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label style="color: var(--color-dark-text); font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block;">Full Name *</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" style="border: 1px solid var(--color-border); border-radius: 8px; padding: 10px 16px; width: 100%; box-sizing: border-box;" required>
            </div>

            <!-- Email -->
            <div>
                <label style="color: var(--color-dark-text); font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block;">Email Address *</label>
                <input type="email" value="{{ auth()->user()->email }}" disabled style="border: 1px solid var(--color-border); border-radius: 8px; padding: 10px 16px; width: 100%; box-sizing: border-box;">
                <p style="color: var(--color-secondary-text); font-size: 12px; margin-top: 8px; margin-bottom: 0;">Contact support to change your email</p>
            </div>

            <!-- Account Type -->
            <div>
                <label style="color: var(--color-dark-text); font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block;">Account Type</label>
                <div style="background-color: #F1F5F9; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px 16px; color: var(--color-secondary-text);">
                    {{ auth()->user()->account_type === 'b2b' ? 'Business (B2B)' : 'Individual (B2C)' }}
                </div>
            </div>

            <!-- Subscription Plan -->
            <div>
                <label style="color: var(--color-dark-text); font-weight: 600; font-size: 14px; margin-bottom: 8px; display: block;">Subscription Plan</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="background-color: #F1F5F9; border: 1px solid var(--color-border); border-radius: 8px; padding: 10px 16px; color: var(--color-secondary-text); flex: 1;">
                        Premium Legal Advisory
                    </div>
                    <span style="background: linear-gradient(135deg, #C7A64A 0%, #D4AF6F 100%); color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Premium</span>
                </div>
            </div>

            <!-- Save Button -->
            <div style="padding-top: 16px;">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Security Settings Tab -->
<div id="security" class="tab-content" style="display: none;">
    <div class="section-card">
        <h2 style="color: var(--color-dark-text); font-size: 20px; font-weight: 700; margin-bottom: 24px;">Security Settings</h2>
        
        <!-- 2FA Recommendation Notice -->
        <div style="background-color: #FFFBEB; border: 1px solid #FCD34D; border-radius: 10px; padding: 16px; display: flex; gap: 12px; margin-bottom: 24px;">
            <svg style="width: 24px; height: 24px; color: #F59E0B; flex-shrink: 0; margin-top: 2px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <p style="color: #92400E; font-weight: 600; font-size: 14px; margin-bottom: 0;">Security Recommendation</p>
                <p style="color: #B45309; font-size: 12px; margin-top: 4px; margin-bottom: 0;">We strongly recommend enabling Two-Factor Authentication (2FA) to protect your account and data. This adds an extra layer of security beyond your password.</p>
            </div>
        </div>
        
        <div style="space-y: 24px;">
            <!-- Change Password -->
            <div style="padding-bottom: 24px; border-bottom: 1px solid var(--color-border);">
                <p style="color: var(--color-dark-text); font-weight: 600; margin-bottom: 8px;">Password</p>
                <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 16px;">Change your password to keep your account secure</p>
                <button type="button" class="btn-secondary">Change Password</button>
            </div>

            <!-- Two-Factor Authentication -->
            <div style="padding: 24px 0; padding-bottom: 24px; border-bottom: 1px solid var(--color-border);">
                <p style="color: var(--color-dark-text); font-weight: 600; margin-bottom: 8px;">Two-Factor Authentication</p>
                <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 16px;">Add an extra layer of security to your account</p>
                <button type="button" class="btn-secondary">Enable 2FA</button>
            </div>

            <!-- Active Sessions -->
            <div style="padding-top: 24px;">
                <p style="color: var(--color-dark-text); font-weight: 600; margin-bottom: 16px;">Active Sessions</p>
                <div style="border: 1px solid var(--color-border); border-radius: 10px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="color: var(--color-dark-text); font-weight: 500; margin-bottom: 4px;">Current Session</p>
                        <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 0;">Safari on macOS • Last active: Just now</p>
                    </div>
                    <span style="background-color: #ECFDF5; color: var(--color-success); padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preferences Tab -->
<div id="preferences" class="tab-content" style="display: none;">
    <div class="section-card">
        <h2 style="color: var(--color-dark-text); font-size: 20px; font-weight: 700; margin-bottom: 24px;">Preferences</h2>
        <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 0;">Your notification and communication preferences will appear here.</p>
    </div>
</div>

<!-- Danger Zone -->
<div class="section-card" style="border: 1px solid var(--color-danger); background-color: #FEF2F2; margin-top: 48px;">
    <h2 style="color: var(--color-danger); font-size: 18px; font-weight: 700; margin-bottom: 16px;">Danger Zone</h2>
    <p style="color: var(--color-secondary-text); font-size: 14px; margin-bottom: 24px;">Irreversible or sensitive actions that require careful consideration</p>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <!-- Download Data -->
        <div style="padding: 16px; border: 1px solid var(--color-border); border-radius: 8px; background-color: white;">
            <p style="color: var(--color-dark-text); font-weight: 600; margin-bottom: 8px;">Download Your Data</p>
            <p style="color: var(--color-secondary-text); font-size: 13px; margin-bottom: 16px;">Export all your personal data in a standard format for your records.</p>
            <button type="button" class="btn-secondary" style="width: 100%;">Download Data</button>
        </div>
        
        <!-- Request Account Deletion -->
        <div style="padding: 16px; border: 1px solid var(--color-danger); border-radius: 8px; background-color: white;">
            <p style="color: var(--color-danger); font-weight: 600; margin-bottom: 8px;">Delete Account</p>
            <p style="color: var(--color-secondary-text); font-size: 13px; margin-bottom: 16px;">Permanently delete your account and all associated data. This cannot be undone.</p>
            <button type="button" class="btn-danger" style="width: 100%;">Request Deletion</button>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).style.display = 'block';
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>
@endsection
