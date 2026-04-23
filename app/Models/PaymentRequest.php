<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'user_id',
        'email',
        'amount',
        'description',
        'payment_type',
        'payable_id',
        'payable_type',
        'status',
        'expires_at',
        'sent_at',
        'viewed_at',
        'metadata',
        'payment_url',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'metadata' => 'json',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_VIEWED = 'viewed';
    const STATUS_PAID = 'paid';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELED = 'canceled';

    /**
     * Get the associated payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the user who created the request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payable entity
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get active requests
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_SENT, self::STATUS_VIEWED])
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired requests
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now())
            ->where('status', '!=', self::STATUS_PAID);
    }

    /**
     * Scope to get pending requests
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_SENT, self::STATUS_VIEWED]);
    }

    /**
     * Mark request as sent
     */
    public function markAsSent()
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
        ]);
        return $this;
    }

    /**
     * Mark request as viewed
     */
    public function markAsViewed()
    {
        if ($this->viewed_at === null) {
            $this->update(['viewed_at' => now()]);
        }
        $this->update(['status' => self::STATUS_VIEWED]);
        return $this;
    }

    /**
     * Mark request as paid
     */
    public function markAsPaid()
    {
        $this->update(['status' => self::STATUS_PAID]);
        return $this;
    }

    /**
     * Cancel the request
     */
    public function cancel()
    {
        $this->update(['status' => self::STATUS_CANCELED]);
        return $this;
    }

    /**
     * Check if request is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now() && $this->status !== self::STATUS_PAID;
    }

    /**
     * Check if request is still valid
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && in_array($this->status, [self::STATUS_SENT, self::STATUS_VIEWED]);
    }

    /**
     * Get the payment URL
     */
    public function getPaymentUrl(): string
    {
        return $this->payment_url ?? route('payments.consultation.form', [
            'amount' => $this->amount,
            'email' => $this->email,
            'name' => 'Customer',
            'request_id' => $this->id,
        ]);
    }
}
