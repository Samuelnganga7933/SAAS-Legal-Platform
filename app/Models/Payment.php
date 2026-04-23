<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stripe_payment_intent_id',
        'payment_type',
        'payable_id',
        'payable_type',
        'user_id',
        'customer_email',
        'customer_name',
        'amount',
        'currency',
        'status',
        'payment_method',
        'stripe_response',
        'failure_message',
        'paid_at',
        'due_date',
        'refunded_at',
        'refunded_amount',
        'description',
        'metadata',
        'ip_address',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
        'refunded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'stripe_response' => 'json',
        'metadata' => 'json',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_CANCELED = 'canceled';

    // Payment type constants
    const TYPE_CONSULTATION = 'consultation';
    const TYPE_SERVICE = 'service';

    /**
     * Get the payable entity (Consultation, Service, etc.)
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user associated with the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get successful payments
     */
    public function scopeSucceeded($query)
    {
        return $query->where('status', self::STATUS_SUCCEEDED);
    }

    /**
     * Scope to get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Mark payment as succeeded
     */
    public function markAsSucceeded($stripeResponse = null)
    {
        $this->update([
            'status' => self::STATUS_SUCCEEDED,
            'paid_at' => now(),
            'stripe_response' => $stripeResponse,
        ]);

        return $this;
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($failureMessage = null, $stripeResponse = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failure_message' => $failureMessage,
            'stripe_response' => $stripeResponse,
        ]);

        return $this;
    }

    /**
     * Mark payment as refunded
     */
    public function markAsRefunded($refundedAmount = null)
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'refunded_at' => now(),
            'refunded_amount' => $refundedAmount ?? $this->amount,
        ]);

        return $this;
    }

    /**
     * Check if payment was successful
     */
    public function isSucceeded(): bool
    {
        return $this->status === self::STATUS_SUCCEEDED;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment has failed
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Get formatted amount in cents for Stripe
     */
    public function getAmountInCents(): int
    {
        return (int) round($this->amount * 100);
    }

    /**
     * Get formatted amount as currency
     */
    public function getFormattedAmount(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}
