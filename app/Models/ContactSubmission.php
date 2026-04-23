<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'country',
        'service',
        'message',
        'consent',
        'status',
        'ip_address',
    ];

    protected $casts = [
        'name' => 'encrypted',
        'email' => 'encrypted',
        'country' => 'encrypted',
        'service' => 'encrypted',
        'message' => 'encrypted',
        'consent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public $timestamps = true;
}
