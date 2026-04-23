<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'user_id',
        'assigned_worker_id',
        'company_id',
        'case_number',
        'title',
        'description',
        'status', // 'open', 'in-progress', 'closed', 'withdrawn'
        'case_category', // 'money_claim', 'contract', 'compliance', 'general'
        'case_type',
        'priority', // 'low', 'medium', 'high'
        'filed_date',
        'closed_date',
        'withdrawn_date',
        'status_timeline',
        'notes',
        'metadata',
        'hourly_billable',
        'fixed_fee',
    ];

    protected $casts = [
        'filed_date' => 'datetime',
        'closed_date' => 'datetime',
        'withdrawn_date' => 'datetime',
        'metadata' => 'json',
        'status_timeline' => 'json',
        'fixed_fee' => 'decimal:2',
        'hourly_billable' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who owns this case
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned worker for this case
     */
    public function assignedWorker()
    {
        return $this->belongsTo(User::class, 'assigned_worker_id');
    }

    /**
     * Get the company handling this case
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all messages related to this case
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get all documents for this case
     */
    public function documents()
    {
        return $this->hasMany(ClientDocument::class, 'case_id');
    }

    /**
     * Get all tasks for this case
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'case_id');
    }

    /**
     * Get all time logs for this case
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class, 'case_id');
    }

    /**
     * Get all internal notes for this case
     */
    public function caseNotes()
    {
        return $this->hasMany(CaseNote::class, 'case_id');
    }

    /**
     * Scope to get active cases
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'in-progress']);
    }

    /**
     * Withdraw a case
     */
    public function withdraw($reason = null)
    {
        $this->update([
            'status' => 'withdrawn',
            'withdrawn_date' => now(),
            'metadata' => array_merge(
                $this->metadata ?? [],
                ['withdrawal_reason' => $reason]
            ),
        ]);
    }

    /**
     * Close a case
     */
    public function close($notes = null)
    {
        $this->updateStatusTimeline('closed');
        $this->update([
            'status' => 'closed',
            'closed_date' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Add entry to status timeline
     */
    public function updateStatusTimeline($status, $notes = null)
    {
        $timeline = $this->status_timeline ?? [];
        
        $timeline[] = [
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'notes' => $notes,
        ];

        $this->update(['status_timeline' => $timeline]);
    }

    /**
     * Get total hours logged for this case
     */
    public function getTotalHoursLogged(): float
    {
        return $this->timeLogs()->sum('hours_logged');
    }

    /**
     * Calculate total billable amount for this case
     */
    public function calculateBillableAmount(): float
    {
        if ($this->fixed_fee) {
            return $this->fixed_fee;
        }

        if ($this->hourly_billable) {
            $totalHours = $this->getTotalHoursLogged();
            $hourlyRate = $this->assignedWorker?->hourly_rate ?? 50;
            return $totalHours * $hourlyRate;
        }

        return 0;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            'open' => 'blue',
            'in-progress' => 'yellow',
            'closed' => 'green',
            'withdrawn' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status icon for UI
     */
    public function getStatusIcon(): string
    {
        return match ($this->status) {
            'open' => '📋',
            'in-progress' => '⏳',
            'closed' => '✅',
            'withdrawn' => '❌',
            default => '❓',
        };
    }
}
