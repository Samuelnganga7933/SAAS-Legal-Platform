<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'case_id',
        'task_id',
        'date',
        'hours_logged',
        'description',
        'billable',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'hours_logged' => 'decimal:2',
            'billable' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user this time log belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the case this time log is for
     */
    public function case()
    {
        return $this->belongsTo(Case::class);
    }

    /**
     * Get the task this time log is for
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Calculate billable amount (hours × hourly rate)
     */
    public function getBillableAmount(): float
    {
        if (!$this->billable) {
            return 0;
        }

        $rate = $this->user->hourly_rate ?? 50; // default $50/hr if not set

        return $this->hours_logged * $rate;
    }

    /**
     * Get total hours logged for a case
     */
    public static function getTotalHoursForCase($caseId): float
    {
        return self::where('case_id', $caseId)->sum('hours_logged');
    }

    /**
     * Get total hours logged by a user for a week
     */
    public static function getTotalHoursForWeek($userId, $startDate = null): float
    {
        $startDate = $startDate ?? now()->startOfWeek();
        $endDate = now()->endOfWeek();

        return self::where('user_id', $userId)
                   ->whereBetween('date', [$startDate, $endDate])
                   ->sum('hours_logged');
    }

    /**
     * Get billable total for a date range
     */
    public static function getBillableTotal($userId, $startDate, $endDate, $caseId = null): float
    {
        $query = self::where('user_id', $userId)
                     ->where('billable', true)
                     ->whereBetween('date', [$startDate, $endDate]);

        if ($caseId) {
            $query->where('case_id', $caseId);
        }

        $total = 0;
        foreach ($query->get() as $log) {
            $total += $log->getBillableAmount();
        }

        return $total;
    }
}
