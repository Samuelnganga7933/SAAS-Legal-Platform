<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class WorkerSettingsController extends Controller
{
    /**
     * Show worker settings
     */
    public function show(): View
    {
        return view('worker.settings', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update worker profile
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        auth()->user()->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Update availability and billing settings
     */
    public function updateAvailability(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hourly_rate' => 'required|numeric|min:0',
            'availability_hours' => 'required|numeric|min:0|max:168',
            'timezone' => 'required|string',
            'work_days' => 'nullable|array',
        ]);

        auth()->user()->update([
            'hourly_rate' => $validated['hourly_rate'],
            'availability_hours' => $validated['availability_hours'],
            'timezone' => $validated['timezone'],
        ]);

        return redirect()->back()->with('success', 'Availability settings updated');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|regex:/[A-Z]/|regex:/[0-9]/',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $settings = [
            'notify_case_assigned' => $request->has('notify_case_assigned'),
            'notify_task_reminder' => $request->has('notify_task_reminder'),
            'notify_messages' => $request->has('notify_messages'),
            'notify_documents' => $request->has('notify_documents'),
            'notify_billing' => $request->has('notify_billing'),
            'email_notifications' => $request->has('email_notifications'),
        ];

        // Store in user settings/preferences (implement based on your User model)
        auth()->user()->update(['notification_settings' => json_encode($settings)]);

        return redirect()->back()->with('success', 'Notification preferences updated');
    }
}
