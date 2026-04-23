<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * Show invitation acceptance page
     */
    public function show($token)
    {
        $user = User::where('invitation_token', $token)
                   ->where('status', 'invited')
                   ->firstOrFail();

        return view('auth.accept-invitation', ['user' => $user, 'token' => $token]);
    }

    /**
     * Accept invitation
     */
    public function accept(Request $request, $token)
    {
        $user = User::where('invitation_token', $token)
                   ->where('status', 'invited')
                   ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'password' => bcrypt($validated['password']),
            'status' => 'active',
            'invitation_token' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Account activated! You can now log in.');
    }
}
