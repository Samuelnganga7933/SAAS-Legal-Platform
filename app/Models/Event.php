<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'type', 'event_date', 'event_time', 'matter_id', 'assigned_to', 'notes', 'created_by'];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function matter()
    {
        return $this->belongsTo(Case::class, 'matter_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeColorClass()
    {
        return match($this->type) {
            'deadline' => 'bg-blue-100 text-blue-900 border-blue-300',
            'task' => 'bg-amber-100 text-amber-900 border-amber-300',
            'call', 'appointment' => 'bg-green-100 text-green-900 border-green-300',
            default => 'bg-gray-100 text-gray-900 border-gray-300',
        };
    }
}
