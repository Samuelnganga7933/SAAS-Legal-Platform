<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Case as CaseModel;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'account_type',
        'email_verification_token',
        'email_verification_token_expires_at',
        'email_verified',
        'is_admin',
        'is_ceo',
        'is_verified_client',
        'email_verified_at',
        'role',
        'role_id',
        'type',
        'status',
        'invitation_token',
        'invitation_sent_at',
        'invited_by',
        'deactivated_at',
        'company_id',
        'subscription_tier',
        'subscription_start',
        'subscription_end',
        'is_subscription_active',
        'stripe_customer_id',
        'hourly_rate',
        'availability_hours',
        'subscription_plan',
        'monthly_rate',
        'subscription_start_date',
        'next_billing_date',
        'cancelled_at',
        'is_subscribed',
        'stripe_card_last_four',
        'stripe_card_brand',
        'stripe_card_expiry',
        // Settings fields
        'job_title',
        'phone',
        'profile_photo_path',
        'notify_case_assigned',
        'notify_message',
        'notify_payment',
        'notify_deadline',
        'notify_team_activity',
        'notify_weekly_digest',
        'theme',
        'google_calendar_connected',
        'google_ads_connected',
        'meta_ads_connected',
        'stripe_connected',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'subscription_start' => 'datetime',
            'subscription_end' => 'datetime',
            'subscription_start_date' => 'datetime',
            'next_billing_date' => 'datetime',
            'cancelled_at' => 'datetime',
            'invitation_sent_at' => 'datetime',
            'deactivated_at' => 'datetime',
            'password' => 'hashed',
            'is_subscription_active' => 'boolean',
            'is_subscribed' => 'boolean',
            'notify_case_assigned' => 'boolean',
            'notify_message' => 'boolean',
            'notify_payment' => 'boolean',
            'notify_deadline' => 'boolean',
            'notify_team_activity' => 'boolean',
            'notify_weekly_digest' => 'boolean',
            'google_calendar_connected' => 'boolean',
            'google_ads_connected' => 'boolean',
            'meta_ads_connected' => 'boolean',
            'stripe_connected' => 'boolean',
            'is_ceo' => 'boolean',
            'is_subscription_active' => 'boolean',
        ];
    }

    /**
     * Get the role of this user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the cases belonging to this user
     */
    public function cases()
    {
        return $this->hasMany(ClientCase::class);
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get the company this user belongs to (for workers/team members)
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get companies this user is a client of
     */
    public function clientCompanies()
    {
        return $this->belongsToMany(Company::class, 'client_company', 'user_id', 'company_id')
                    ->withPivot('assigned_worker_id', 'case_category', 'hourly_rate_override')
                    ->withTimestamps();
    }

    /**
     * Get cases assigned to this worker
     */
    public function assignedCases()
    {
        return $this->hasMany(CaseModel::class, 'assigned_worker_id');
    }

    /**
     * Get tasks assigned to this user
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to_user_id');
    }

    /**
     * Get tasks created by this user
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by_user_id');
    }

    /**
     * Get time logs for this user
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Get documents uploaded by this user
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(ClientDocument::class, 'uploaded_by_user_id');
    }

    /**
     * Get payments for this user/company
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get team members under this user's company (if user is admin)
     */
    public function teamMembers()
    {
        return $this->company()
                    ->first()
                    ?->workers()
                    ->where('users.id', '!=', $this->id)
                    ->get() ?? collect();
    }

    /**
     * Check if user is CEO
     */
    public function isCeo(): bool
    {
        return (bool) $this->is_ceo;
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // CEO has all permissions
        if ($this->isCeo()) {
            return true;
        }

        // Must have a role for lower permission checks
        if (!$this->role) {
            return false;
        }

        return in_array($permission, $this->role->permissions ?? []);
    }

    /**
     * Check if user is a worker
     */
    public function isWorker(): bool
    {
        return $this->type === 'worker';
    }

    /**
     * Check if user is a client
     */
    public function isClient(): bool
    {
        return $this->type === 'client';
    }

    /**
     * Check if user is an admin (legacy - kept for compatibility)
     */
    public function isAdminUser(): bool
    {
        return $this->role === 'admin' || $this->is_admin;
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
     * Get subscription status text
     */
    public function getSubscriptionStatus(): ?string
    {
        if (!$this->hasActiveSubscription()) {
            return null;
        }

        return $this->subscription_tier ?? 'unknown';
    }

    /**
     * Check if user is CEO
     */
    public function isCEO(): bool
    {
        return $this->role === 'ceo';
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is Employee
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Get team members invited by this user
     */
    public function invitedMembers()
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    /**
     * Get the user who invited this member
     */
    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Get count of cases assigned to this user
     */
    public function getMattersCount(): int
    {
        return $this->assignedCases()->count();
    }
}
