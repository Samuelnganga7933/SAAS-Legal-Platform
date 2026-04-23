<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\Consultation;
use PHPUnit\Framework\TestCase;

class PaymentModelTest extends TestCase
{
    /**
     * Test payment model constants
     */
    public function test_payment_status_constants()
    {
        $this->assertEquals('pending', Payment::STATUS_PENDING);
        $this->assertEquals('processing', Payment::STATUS_PROCESSING);
        $this->assertEquals('succeeded', Payment::STATUS_SUCCEEDED);
        $this->assertEquals('failed', Payment::STATUS_FAILED);
        $this->assertEquals('refunded', Payment::STATUS_REFUNDED);
        $this->assertEquals('canceled', Payment::STATUS_CANCELED);
    }

    /**
     * Test payment type constants
     */
    public function test_payment_type_constants()
    {
        $this->assertEquals('consultation', Payment::TYPE_CONSULTATION);
        $this->assertEquals('service', Payment::TYPE_SERVICE);
    }

    /**
     * Test amount in cents conversion
     */
    public function test_get_amount_in_cents()
    {
        $payment = new Payment([
            'amount' => 99.99,
        ]);

        $this->assertEquals(9999, $payment->getAmountInCents());
    }

    /**
     * Test formatted amount string
     */
    public function test_get_formatted_amount()
    {
        $payment = new Payment([
            'amount' => 99.99,
            'currency' => 'USD',
        ]);

        $this->assertEquals('99.99 USD', $payment->getFormattedAmount());
    }
}
