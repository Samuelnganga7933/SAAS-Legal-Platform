<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailVerificationService;
use App\Services\RecaptchaService;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected EmailVerificationService $emailVerificationService;
    protected RecaptchaService $recaptchaService;

    public function __construct(
        EmailVerificationService $emailVerificationService,
        RecaptchaService $recaptchaService
    ) {
        $this->emailVerificationService = $emailVerificationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Register a new user and send verification email
     */
    public function register(Request $request)
    {
        // Verify reCAPTCHA token (if provided)
        if ($request->filled('recaptcha_token')) {
            if (!$this->recaptchaService->verify($request->input('recaptcha_token'), 'register')) {
                return redirect('/register')
                    ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                    ->withInput();
            }
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'account_type' => 'required|in:b2c,b2b',
            'terms' => 'required|accepted', // Must be checked
        ]);

        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator)
                ->withInput();
        }

        // Create the user (not verified yet)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
            'email_verified' => false,
        ]);

        // Generate and store encrypted verification token
        $plainToken = $this->emailVerificationService->generateToken($user);

        // Send verification email
        $user->notify(new VerifyEmailNotification($plainToken));

        // Redirect to verification pending page
        return redirect()->route('email-verification.pending')
            ->with('success', 'Account created! Please check your email to verify your account.');
    }

    /**
     * Verify email address with token
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect('/login')
                ->withErrors(['email' => 'Invalid verification link.']);
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/login')
                ->withErrors(['email' => 'User not found.']);
        }

        // Verify the token
        if (!$this->emailVerificationService->verifyToken($user, $token)) {
            return redirect()->route('email-verification.pending')
                ->withErrors(['token' => 'Invalid or expired verification token.']);
        }

        // Mark email as verified
        $this->emailVerificationService->markAsVerified($user);

        // Log in the user
        Auth::login($user);

        return redirect()->route('account-created')
            ->with('success', 'Email verified successfully! Welcome to ' . config('app.name') . '!');
    }

    /**
     * Show email verification pending page
     */
    public function showVerificationPending()
    {
        return view('auth.verify-email');
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'User not found.']);
        }

        if ($user->email_verified) {
            return redirect()->back()
                ->withErrors(['email' => 'Email is already verified.']);
        }

        // Generate new token and send email
        $plainToken = $this->emailVerificationService->regenerateToken($user);
        $user->notify(new VerifyEmailNotification($plainToken));

        return redirect()->back()
            ->with('success', 'Verification email sent! Please check your email.');
    }

    /**
     * Verify login credentials
     */
    public function login(Request $request)
    {
        // Verify reCAPTCHA token (if provided)
        if ($request->filled('recaptcha_token')) {
            if (!$this->recaptchaService->verify($request->input('recaptcha_token'), 'login')) {
                return redirect('/login')
                    ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                    ->withInput();
            }
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect('/login')
                ->withErrors($validator)
                ->withInput();
        }

        // Find user
        $user = User::where('email', $request->email)->first();

        // Attempt to log in
        if (Auth::attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ],
            $request->filled('remember')
        )) {
            // Determine redirect location based on user role
            $redirectRoute = Auth::user()->is_admin ? '/admin/dashboard' : '/dashboard';
            $warningMessage = 'Please verify your email address. Check your inbox for the verification link.';
            
            // Check if email is verified - if not, show reminder
            if (!Auth::user()->email_verified) {
                return redirect($redirectRoute)
                    ->with('warning', $warningMessage)
                    ->with('show_email_verification_reminder', true);
            }
            
            return redirect($redirectRoute)->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Login failed
        return redirect('/login')
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput();
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
