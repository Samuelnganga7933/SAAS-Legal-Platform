<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's authentication page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google
     */
    public function handleGoogleCallback()
    {
        try {
            $google_user = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Failed to authenticate with Google');
        }

        // Check if user exists or create new user
        $user = User::updateOrCreate(
            ['email' => $google_user->getEmail()],
            [
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'google_id' => $google_user->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt('google-oauth-' . uniqid()), // Random password for OAuth users
            ]
        );

        // Login the user
        Auth::login($user, remember: true);

        return redirect('/dashboard')->with('success', 'Welcome ' . $user->name . '!');
    }
}
