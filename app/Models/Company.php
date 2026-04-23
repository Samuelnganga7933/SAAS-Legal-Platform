<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'subscription_tier',
        'subscription_start',
        'subscription_end',
        'is_subscription_active',
        'tax_id',
        'stripe_customer_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'subscription_start' => 'datetime',
            'subscription_end' => 'datetime',
            'is_subscription_active' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get workers belonging to this company
     */
    public function workers()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get clients served by this company
     */
    public function clients()
    {
        return $this->belongsToMany(User::class, 'client_company', 'company_id', 'user_id')
                    ->withPivot('assigned_worker_id', 'case_category', 'hourly_rate_override')
                    ->withTimestamps();
    }

    /**
     * Get cases for this company
     */
    public function cases()
    {
        return $this->hasMany(Case::class);
    }

    /**
     * Get payments for this company
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get payment requests for this company
     */
    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class);
    }

    /**
     * Check if subscription is active
     */
    public function hasActiveSubscription(): bool
    {
        if (!$this->is_subscription_active) {
            return false;
        }

        if ($this->subscription_end && $this->subscription_end < now()) {
            return false;
        }

        return true;
    }

    /**
     * Get subscription status
     */
    public function getSubscriptionStatus(): ?string
    {
        if (!$this->hasActiveSubscription()) {
            return null;
        }

        return $this->subscription_tier ?? 'unknown';
    }

    /**
     * Get case count vs tier limit
     */
    public function getCaseCountStats(): array
    {
        $activeCases = $this->cases()->where('status', '!=', 'withdrawn')->count();
        
        $limits = [
            'starter' => 3,
            'professional' => 15,
            'enterprise' => PHP_INT_MAX,
        ];

        $tierLimit = $limits[$this->subscription_tier] ?? 5;

        return [
            'active_cases' => $activeCases,
            'tier_limit' => $tierLimit,
            'overage_count' => max(0, $activeCases - $tierLimit),
            'overage_fee' => max(0, $activeCases - $tierLimit) * 50, // $50 per overage case
        ];
    }
}
