<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class InboxItem extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'course_id',
        'task_id',
        'type',
        'title',
        'url',
        'note',
        'captured_at',
        'source',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    // Relations
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    // Mutator: normalize URL
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = trim($value);
    }

    // Accessor: Get domain from URL
    public function getDomainAttribute(): ?string
    {
        if (empty($this->url)) {
            return null;
        }

        $parsedUrl = parse_url($this->url);
        return $parsedUrl['host'] ?? null;
    }
}
