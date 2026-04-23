<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientComment extends Model
{
    protected $table = 'client_comments';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'location',
        'comment',
        'rating',
        'is_verified',
        'is_approved',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
