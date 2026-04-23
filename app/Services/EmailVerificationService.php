<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;

class EmailVerificationService
{
    protected Encrypter $encrypter;
    protected int $tokenExpirationHours = 24;

    public function __construct()
    {
        $this->encrypter = app('encrypter');
    }

    /**
     * Generate and encrypt an email verification token
     */
    public function generateToken(User $user): string
    {
        // Generate a random token
        $plainToken = Str::random(64);

        // Encrypt the token
        $encryptedToken = $this->encrypter->encrypt($plainToken);

        // Save encrypted token and expiration to user
        $user->update([
            'email_verification_token' => $encryptedToken,
            'email_verification_token_expires_at' => now()->addHours($this->tokenExpirationHours),
        ]);

        return $plainToken;
    }

    /**
     * Verify the email token
     */
    public function verifyToken(User $user, string $plainToken): bool
    {
        // Check if token has expired
        if ($user->email_verification_token_expires_at === null || now()->isAfter($user->email_verification_token_expires_at)) {
            return false;
        }

        // Check if token exists and decrypt it to compare
        if ($user->email_verification_token === null) {
            return false;
        }

        try {
            $decryptedToken = $this->encrypter->decrypt($user->email_verification_token);
            return $decryptedToken === $plainToken;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Mark email as verified and clear token
     */
    public function markAsVerified(User $user): void
    {
        $user->update([
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_token_expires_at' => null,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Check if email is verified
     */
    public function isVerified(User $user): bool
    {
        return $user->email_verified === true;
    }

    /**
     * Regenerate token (for resending verification)
     */
    public function regenerateToken(User $user): string
    {
        // Clear old token
        $user->update([
            'email_verification_token' => null,
            'email_verification_token_expires_at' => null,
        ]);

        // Generate new token
        return $this->generateToken($user);
    }
}
