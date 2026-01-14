<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'channel',
        'to_email',
        'task_id',
        'meta',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'meta' => 'array',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
