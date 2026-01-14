<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\HasTags;

class Material extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'course_id',
        'title',
        'type',
        'url',
        'note',
        'source',
        'captured_at',
        'inbox_item_id',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    // Relations
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function inboxItem(): BelongsTo
    {
        return $this->belongsTo(InboxItem::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'material_task');
    }

    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachmentable', 'attachmentables')
            ->withPivot('role')
            ->withTimestamps();
    }
}
