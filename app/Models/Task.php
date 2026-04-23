<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'assigned_to_user_id',
        'case_id',
        'created_by_user_id',
        'due_date',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the case this task belongs to
     */
    public function case()
    {
        return $this->belongsTo(Case::class);
    }

    /**
     * Get the user this task is assigned to
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get the user who created this task
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get time logs for this task
     */
    public function timeLogs()
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date < today() && $this->status !== 'done' && $this->status !== 'cancelled';
    }

    /**
     * Check if task is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'done';
    }

    /**
     * Mark task as in progress
     */
    public function markInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    /**
     * Mark task as done
     */
    public function markDone()
    {
        $this->update(['status' => 'done']);
    }

    /**
     * Mark task as cancelled
     */
    public function markCancelled()
    {
        $this->update(['status' => 'cancelled']);
    }
}
