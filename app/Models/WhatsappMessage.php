<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    protected $fillable = [
        'message_id', 'instance_id', 'from_number', 'to_number',
        'body', 'type', 'sender_name', 'is_group', 'is_read', 'message_at',
    ];

    protected function casts(): array
    {
        return [
            'is_group' => 'boolean',
            'is_read' => 'boolean',
            'message_at' => 'datetime',
        ];
    }

    /**
     * Get a clean phone number (without @c.us suffix).
     */
    public function getCleanFromAttribute(): string
    {
        return str_replace('@c.us', '', $this->from_number);
    }
}
