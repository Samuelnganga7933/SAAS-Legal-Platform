<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.index');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'job_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateNotifications(Request $request)
    {
        $user = $request->user();

        $notifications = [
            'notify_case_assigned' => $request->has('notify_case_assigned'),
            'notify_message' => $request->has('notify_message'),
            'notify_payment' => $request->has('notify_payment'),
            'notify_deadline' => $request->has('notify_deadline'),
            'notify_team_activity' => $request->has('notify_team_activity'),
            'notify_weekly_digest' => $request->has('notify_weekly_digest'),
        ];

        $user->update($notifications);

        return back()->with('success', 'Notification preferences updated.');
    }

    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Theme preference updated.');
    }

    public function updateIntegrations(Request $request)
    {
        $this->authorize('isCEO', $request->user());

        $user = $request->user();

        $integrations = [
            'google_calendar_connected' => $request->has('google_calendar_connected'),
            'google_ads_connected' => $request->has('google_ads_connected'),
            'meta_ads_connected' => $request->has('meta_ads_connected'),
        ];

        $user->update($integrations);

        return back()->with('success', 'Integrations updated.');
    }

    public function deleteAccount(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        auth()->logout();

        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    public function resetPlatform(Request $request)
    {
        $this->authorize('isCEO', $request->user());

        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Reset all data
        \App\Models\Case::query()->forceDelete();
        \App\Models\CrmContact::query()->forceDelete();
        \App\Models\Event::query()->forceDelete();
        \App\Models\Payment::query()->forceDelete();
        \App\Models\User::query()->where('role', '!=', 'ceo')->forceDelete();

        return back()->with('success', 'Platform has been reset to initial state.');
    }
}
