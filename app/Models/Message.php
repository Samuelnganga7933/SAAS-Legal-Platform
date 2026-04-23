<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'case_id',
        'subject',
        'body',
        'message_type', // 'direct', 'broadcast'
        'delivery_status', // 'pending', 'delivered', 'failed'
        'delivered_at',
        'delivery_error',
        'is_read',
        'read_at',
        'is_encrypted',
        'deleted_by_sender',
        'deleted_by_recipient',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_encrypted' => 'boolean',
        'delivered_at' => 'datetime',
        'deleted_by_sender' => 'boolean',
        'deleted_by_recipient' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the case this message is related to
     */
    public function case()
    {
        return $this->belongsTo(ClientCase::class);
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'delivery_status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark message as failed
     */
    public function markAsFailed($error = null)
    {
        $this->update([
            'delivery_status' => 'failed',
            'delivery_error' => $error,
        ]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for direct messages
     */
    public function scopeDirect($query)
    {
        return $query->where('message_type', 'direct');
    }

    /**
     * Scope for broadcast messages
     */
    public function scopeBroadcast($query)
    {
        return $query->where('message_type', 'broadcast');
    }

    /**
     * Scope for delivered messages
     */
    public function scopeDelivered($query)
    {
        return $query->where('delivery_status', 'delivered');
    }

    /**
     * Scope for pending messages
     */
    public function scopePending($query)
    {
        return $query->where('delivery_status', 'pending');
    }
}
