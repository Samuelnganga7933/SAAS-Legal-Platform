<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);
    }

    /**
     * Test creating a payment intent
     */
    public function test_can_create_payment_intent()
    {
        $response = $this->postJson('/payments/create-intent', [
            'amount' => 99.99,
            'email' => 'test@example.com',
            'customer_name' => 'John Doe',
            'description' => 'Test Payment',
            'payment_type' => 'consultation',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'clientSecret',
            'paymentId',
            'amount',
            'currency',
        ]);

        $this->assertDatabaseHas('payments', [
            'customer_email' => 'test@example.com',
            'customer_name' => 'John Doe',
            'amount' => 99.99,
            'status' => Payment::STATUS_PENDING,
        ]);
    }

    /**
     * Test checking payment status
     */
    public function test_can_check_payment_status()
    {
        $payment = Payment::factory()->create([
            'status' => Payment::STATUS_SUCCEEDED,
            'amount' => 99.99,
        ]);

        $response = $this->getJson('/payments/' . $payment->id . '/status');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $payment->id,
            'status' => Payment::STATUS_SUCCEEDED,
            'amount' => '99.99',
        ]);
    }

    /**
     * Test payment success page
     */
    public function test_can_view_payment_success_page()
    {
        $payment = Payment::factory()->create([
            'status' => Payment::STATUS_SUCCEEDED,
        ]);

        $response = $this->get('/payments/success?payment_id=' . $payment->id);

        $response->assertStatus(200);
        $response->assertViewHas('payment', $payment);
    }

    /**
     * Test payment failure page
     */
    public function test_can_view_payment_failure_page()
    {
        $payment = Payment::factory()->create([
            'status' => Payment::STATUS_FAILED,
            'failure_message' => 'Card declined',
        ]);

        $response = $this->get('/payments/failure?payment_id=' . $payment->id);

        $response->assertStatus(200);
        $response->assertViewHas('payment', $payment);
    }

    /**
     * Test getting payment history
     */
    public function test_can_get_payment_history()
    {
        Payment::factory(3)->create([
            'customer_email' => 'test@example.com',
            'status' => Payment::STATUS_SUCCEEDED,
        ]);

        Payment::factory(2)->create([
            'customer_email' => 'other@example.com',
            'status' => Payment::STATUS_SUCCEEDED,
        ]);

        $response = $this->getJson('/payments/history?email=test@example.com');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'payments');
    }

    /**
     * Test create payment intent with missing required fields
     */
    public function test_create_payment_intent_requires_amount()
    {
        $response = $this->postJson('/payments/create-intent', [
            'email' => 'test@example.com',
            'customer_name' => 'John Doe',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test refund payment requires authentication
     */
    public function test_refund_payment_requires_auth()
    {
        $payment = Payment::factory()->create([
            'status' => Payment::STATUS_SUCCEEDED,
        ]);

        $response = $this->postJson('/payments/' . $payment->id . '/refund');

        $response->assertUnauthorized();
    }

    /**
     * Test cancel payment requires authentication
     */
    public function test_cancel_payment_requires_auth()
    {
        $payment = Payment::factory()->create([
            'status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->postJson('/payments/' . $payment->id . '/cancel');

        $response->assertUnauthorized();
    }
}
