<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email',
        'full_name',
        'phone',
        'preferred_date_time',
        'description',
        'status',
        'zoom_link',
        'ip_address',
    ];

    protected $casts = [
        'email' => 'encrypted',
        'full_name' => 'encrypted',
        'phone' => 'encrypted',
        'description' => 'encrypted',
        'zoom_link' => 'encrypted',
        'preferred_date_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Get payments for this consultation (polymorphic relation)
     */
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Get the successful payments for this consultation
     */
    public function succeededPayments()
    {
        return $this->payments()->where('status', Payment::STATUS_SUCCEEDED);
    }

    /**
     * Check if consultation has been paid
     */
    public function isPaid(): bool
    {
        return $this->succeededPayments()->exists();
    }

    /**
     * Get the total amount paid for this consultation
     */
    public function getTotalPaid(): float
    {
        return (float) $this->succeededPayments()->sum('amount');
    }
}
