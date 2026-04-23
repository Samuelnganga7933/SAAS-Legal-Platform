<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    protected string $secretKey;
    protected float $scoreThreshold;

    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->scoreThreshold = config('services.recaptcha.score_threshold', 0.5);
    }

    /**
     * Verify the reCAPTCHA token with Google
     */
    public function verify(string $token, ?string $action = null): bool
    {
        if (empty($this->secretKey)) {
            // If no secret key is configured, skip verification in development
            return app()->environment('local', 'testing');
        }

        try {
            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => $this->secretKey,
                    'response' => $token,
                ]
            );

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();

            // Check if the verification was successful
            if (!($data['success'] ?? false)) {
                return false;
            }

            // Verify action matches if provided
            if ($action && ($data['action'] ?? null) !== $action) {
                return false;
            }

            // Check score (for v3 reCAPTCHA)
            $score = $data['score'] ?? null;
            if ($score !== null && $score < $this->scoreThreshold) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            return false;
        }
    }
}
