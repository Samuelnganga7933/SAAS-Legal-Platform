<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Stringable;

class ContactController extends Controller
{
    /**
     * Store contact form submission
     * Security: CSRF token validation, rate limiting, input sanitization
     */
    public function store(Request $request)
    {
        // Rate limiting: 5 submissions per hour per IP
        $key = 'contact-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Too many contact attempts. Please try again later.']);
        }
        RateLimiter::hit($key, 3600);

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\'-]+$/',
            'email' => 'required|email|max:255',
            'country' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'consent' => 'required|accepted',
        ], [
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'consent.required' => 'You must agree to the privacy policy.',
        ]);

        try {
            // Create submission with encryption
            ContactSubmission::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'country' => $validated['country'] ?? null,
                'service' => $validated['service'] ?? null,
                'message' => $validated['message'],
                'consent' => true,
                'status' => 'pending',
                'ip_address' => $this->maskIpAddress($request->ip()),
            ]);

            Log::info('Contact submission received', [
                'email' => $validated['email'],
                'ip' => $this->maskIpAddress($request->ip()),
                'timestamp' => now(),
            ]);

            return back()->with('success', 'Thank you for contacting us! We will respond shortly.');
        } catch (\Exception $e) {
            Log::error('Contact form error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'An error occurred. Please try again later.']);
        }
    }

    /**
     * Store consultation booking
     * Security: Rate limiting, input validation, data encryption
     */
    public function signup(Request $request)
    {
        // Rate limiting: 3 signups per day per IP
        $key = 'signup-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['email' => 'Too many signup attempts. Please try again tomorrow.']);
        }
        RateLimiter::hit($key, 86400);

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\'-]+$/',
            'phone' => 'nullable|regex:/^[0-9+\-\s\(\)]{7,20}$/',
            'preferred_date_time' => 'nullable|date_format:Y-m-d\TH:i',
        ], [
            'email.email' => 'Please enter a valid email address.',
            'full_name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        try {
            Consultation::create([
                'email' => $validated['email'],
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'preferred_date_time' => $validated['preferred_date_time'] ?? null,
                'status' => 'pending',
                'ip_address' => $this->maskIpAddress($request->ip()),
            ]);

            Log::info('Consultation request received', [
                'email' => $validated['email'],
                'ip' => $this->maskIpAddress($request->ip()),
            ]);

            return back()->with('success', 'Thanks for booking! We\'ll confirm your consultation shortly.');
        } catch (\Exception $e) {
            Log::error('Consultation booking error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'An error occurred. Please try again later.']);
        }
    }

    /**
     * Create user account
     * Security: Password hashing, CSRF protection, email validation, strong password requirements
     */
    public function createAccount(Request $request)
    {
        // Rate limiting: 5 account creations per day per IP
        $key = 'account-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Too many account creation attempts. Please try again tomorrow.']);
        }
        RateLimiter::hit($key, 86400);

        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ], [
            'email.unique' => 'An account with this email already exists.',
            'password.regex' => 'Password must contain uppercase, lowercase, numbers, and special characters.',
            'password.min' => 'Password must be at least 12 characters long.',
        ]);

        try {
            User::create([
                'name' => explode('@', $validated['email'])[0],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('User account created', [
                'email' => $validated['email'],
                'ip' => $this->maskIpAddress($request->ip()),
            ]);

            return back()->with('success', 'Account created successfully! You can now log in.');
        } catch (\Exception $e) {
            Log::error('Account creation error', ['error' => $e->getMessage()]);
            return back()->withErrors(['general' => 'An error occurred. Please try again later.']);
        }
    }

    /**
     * Mask IP address for privacy
     */
    private function maskIpAddress($ip)
    {
        // IPv4
        if (str_contains($ip, '.')) {
            $parts = explode('.', $ip);
            return $parts[0] . '.' . $parts[1] . '.***.**';
        }
        // IPv6
        return substr($ip, 0, 10) . '...';
    }
}
