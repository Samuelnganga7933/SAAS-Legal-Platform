<?php

namespace Database\Factories;

use App\Models\PaymentRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentRequest>
 */
class PaymentRequestFactory extends Factory
{
    protected $model = PaymentRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_id' => null,
            'user_id' => null,
            'email' => $this->faker->safeEmail(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(),
            'payment_type' => 'consultation',
            'payable_id' => null,
            'payable_type' => null,
            'status' => PaymentRequest::STATUS_SENT,
            'expires_at' => now()->addDays(7),
            'sent_at' => now(),
            'viewed_at' => null,
            'metadata' => json_encode([]),
            'payment_url' => null,
        ];
    }

    /**
     * Mark request as sent
     */
    public function sent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PaymentRequest::STATUS_SENT,
                'sent_at' => now(),
            ];
        });
    }

    /**
     * Mark request as viewed
     */
    public function viewed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PaymentRequest::STATUS_VIEWED,
                'viewed_at' => now(),
            ];
        });
    }

    /**
     * Mark request as paid
     */
    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PaymentRequest::STATUS_PAID,
                'viewed_at' => now(),
            ];
        });
    }

    /**
     * Set request as expired
     */
    public function expired(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PaymentRequest::STATUS_EXPIRED,
                'expires_at' => now()->subDays(1),
            ];
        });
    }
}
