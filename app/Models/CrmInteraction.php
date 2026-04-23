<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmInteraction extends Model
{
    protected $table = 'crm_interactions';

    protected $fillable = [
        'contact_id',
        'user_id',
        'type',
        'summary',
        'interaction_date',
    ];

    protected $casts = [
        'interaction_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(CrmContact::class, 'contact_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabel()
    {
        return ucfirst($this->type);
    }
}
