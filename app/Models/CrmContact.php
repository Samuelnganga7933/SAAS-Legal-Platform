<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmContact extends Model
{
    use SoftDeletes;

    protected $table = 'crm_contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'source',
        'status',
        'notes',
        'assigned_to',
        'created_by',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function interactions()
    {
        return $this->hasMany(CrmInteraction::class, 'contact_id')->orderBy('interaction_date', 'desc');
    }

    public function getLastInteraction()
    {
        return $this->interactions()->first();
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'lead' => 'bg-amber-100 text-amber-900',
            'prospect' => 'bg-blue-100 text-blue-900',
            'client' => 'bg-green-100 text-green-900',
            'inactive' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getSourceLabel()
    {
        return ucfirst($this->source);
    }
}
