<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stripe_payment_intent_id' => 'pi_' . $this->faker->unique()->bothify('????????????????????'),
            'payment_type' => $this->faker->randomElement([Payment::TYPE_CONSULTATION, Payment::TYPE_SERVICE]),
            'payable_id' => null,
            'payable_type' => null,
            'user_id' => null,
            'customer_email' => $this->faker->unique()->safeEmail(),
            'customer_name' => $this->faker->name(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'USD',
            'status' => $this->faker->randomElement([
                Payment::STATUS_PENDING,
                Payment::STATUS_PROCESSING,
                Payment::STATUS_SUCCEEDED,
                Payment::STATUS_FAILED,
            ]),
            'payment_method' => 'card',
            'stripe_response' => json_encode([
                'id' => 'pi_' . $this->faker->bothify('????????????????????'),
                'status' => Payment::STATUS_SUCCEEDED,
                'amount' => 9999,
                'currency' => 'usd',
            ]),
            'failure_message' => null,
            'paid_at' => $this->faker->randomElement([null, now()]),
            'refunded_at' => null,
            'refunded_amount' => null,
            'description' => $this->faker->sentence(),
            'metadata' => json_encode([]),
            'ip_address' => $this->faker->ipv4(),
        ];
    }

    /**
     * Mark payment as succeeded
     */
    public function succeeded(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Payment::STATUS_SUCCEEDED,
                'paid_at' => now(),
            ];
        });
    }

    /**
     * Mark payment as failed
     */
    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Payment::STATUS_FAILED,
                'failure_message' => 'Card declined',
            ];
        });
    }

    /**
     * Mark payment as pending
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Payment::STATUS_PENDING,
                'paid_at' => null,
            ];
        });
    }

    /**
     * Set payment type to consultation
     */
    public function consultation(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_type' => Payment::TYPE_CONSULTATION,
            ];
        });
    }

    /**
     * Set payment type to service
     */
    public function service(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_type' => Payment::TYPE_SERVICE,
            ];
        });
    }
}
